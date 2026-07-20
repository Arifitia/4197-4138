<?php

namespace App\Controllers;

use App\Libraries\FraisService;
use App\Models\ClientModel;
use App\Models\MajSoldeModel;
use App\Models\PrefixeModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;

/**
 * Gère la logique métier des opérations financières.
 * Attend un client déjà identifié (client_id transmis en POST,
 * ex: rempli côté vue à partir de la session utilisateur).
 */
class OperationController extends BaseController
{
    protected ClientModel $clientModel;
    protected TransactionModel $transactionModel;
    protected MajSoldeModel $majSoldeModel;
    protected TypeOperationModel $typeOperationModel;
    protected PrefixeModel $prefixeModel;
    protected FraisService $fraisService;
    protected PrefixeModel $prefixeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel        = new ClientModel();
        $this->transactionModel   = new TransactionModel();
        $this->majSoldeModel      = new MajSoldeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->prefixeModel       = new PrefixeModel();
        $this->fraisService       = new FraisService();
        $this->prefixeModel       = new PrefixeModel();
    }

    /**
     * Effectue un dépôt (supposé automatique, pas de frais).
     */
    public function depot()
    {
        $clientId = (int) $this->request->getPost('client_id');
        $montant  = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Montant invalide.']);
        }

        $client = $this->clientModel->find($clientId);
        if ($client === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client introuvable.']);
        }

        $typeId = $this->typeOperationModel->getIdByNom('depot');
        $frais  = $this->fraisService->calculerFrais($typeId, $montant);

        $soldeAvant = (int) $client['solde'];
        $soldeApres = $soldeAvant + (int) $montant;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->clientModel->update($clientId, ['solde' => $soldeApres]);

        $transactionId = $this->transactionModel->insert([
            'client_id'         => $clientId,
            'type_operation_id' => $typeId,
            'montant'           => $montant,
            'frais'             => $frais,
        ], true);

        $this->majSoldeModel->insert([
            'transaction_id' => $transactionId,
            'client_id'      => $clientId,
            'solde_avant'    => $soldeAvant,
            'solde_apres'    => $soldeApres,
        ]);

        $db->transComplete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Dépôt effectué.',
            'solde'   => $soldeApres,
        ]);
    }

    /**
     * Effectue un retrait (montant + frais déduits du solde).
     * Si withdraw_fee_paid est vrai, ne prélève pas les frais.
     */
    public function retrait()
    {
        $clientId = (int) $this->request->getPost('client_id');
        $montant  = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Montant invalide.']);
        }

        $client = $this->clientModel->find($clientId);
        if ($client === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client introuvable.']);
        }

        $typeId = $this->typeOperationModel->getIdByNom('retrait');
        
        // Récupère la dernière transaction de transfert pour ce client pour vérifier si frais sont payés
        $lastTransfertWithPrepaidFees = $this->transactionModel
            ->where('client_destinataire_id', $clientId)
            ->where('withdraw_fee_paid', 1)
            ->orderBy('date_transaction', 'DESC')
            ->first();
        
        // Si frais ont déjà été payés par l'expéditeur, ne pas refacturer
        $frais = $lastTransfertWithPrepaidFees ? 0 : $this->fraisService->calculerFrais($typeId, $montant);
        $total  = $montant + $frais;

        $soldeAvant = (int) $client['solde'];

        if ($soldeAvant < $total) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solde insuffisant.']);
        }

        $soldeApres = $soldeAvant - (int) $total;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->clientModel->update($clientId, ['solde' => $soldeApres]);

        $transactionId = $this->transactionModel->insert([
            'client_id'         => $clientId,
            'type_operation_id' => $typeId,
            'montant'           => $montant,
            'frais'             => $frais,
        ], true);

        $this->majSoldeModel->insert([
            'transaction_id' => $transactionId,
            'client_id'      => $clientId,
            'solde_avant'    => $soldeAvant,
            'solde_apres'    => $soldeApres,
        ]);

        $db->transComplete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Retrait effectué.',
            'solde'   => $soldeApres,
        ]);
    }

    /**
     * Effectue un transfert vers un autre client ou vers un opérateur externe.
     * Supporty withdraw fee prepayment (frais payés par l'expéditeur).
     * Pas de prépaiement de frais pour les transferts externes.
     */
    public function transfert()
    {
        $clientId            = (int) $this->request->getPost('client_id');
        $numeroDestinataire  = trim((string) $this->request->getPost('numero_destinataire'));
        $montant             = (float) $this->request->getPost('montant');
        $payerFraisRetrait   = (int) $this->request->getPost('payer_frais_retrait') === 1;

        if ($montant <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Montant invalide.']);
        }

        $expediteur = $this->clientModel->find($clientId);
        if ($expediteur === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client introuvable.']);
        }

        $destinataireExterneCode = $this->prefixeModel->getOperateurExterne($numeroDestinataire);
        $destinataireExterneNumero = $destinataireExterneCode !== null ? $numeroDestinataire : null;

        $typeId = $this->typeOperationModel->getIdByNom('transfert');
        $frais  = $this->fraisService->calculerFrais($typeId, $montant);
        $total  = $montant + $frais;

        $soldeAvantExp = (int) $expediteur['solde'];

        if ($soldeAvantExp < $total) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solde insuffisant.']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $soldeApresExp = $soldeAvantExp - (int) $total;
        $this->clientModel->update($clientId, ['solde' => $soldeApresExp]);

        $destinataireExterneCode = $this->prefixeModel->getOperateurExterne($numeroDestinataire);
        $destinataireExterneNumero = $destinataireExterneCode !== null ? $numeroDestinataire : null;

        $fraisRetrait = 0;
        $withdrawFeePaid = 0;
        $clientDestinataireId = null;

        // Vérifier si le destinataire est interne (MVola)
        if ($destinataireExterneCode === null) {
            $destinataire = $this->clientModel->findByNumero($numeroDestinataire);
            if ($destinataire === null) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Destinataire introuvable.']);
            }

            if ((int) $destinataire['id'] === $clientId) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Impossible de se transférer à soi-même.']);
            }

            $clientDestinataireId = (int) $destinataire['id'];

            // Option: payer les frais de retrait du destinataire (uniquement pour transferts MVola)
            if ($payerFraisRetrait) {
                $fraisRetrait = $this->fraisService->calculerFrais(
                    $this->typeOperationModel->getIdByNom('retrait'),
                    $montant
                );
                $withdrawFeePaid = 1;
            }
        }

        $total = $montant + $fraisTransfert + $fraisRetrait;
        $soldeAvantExp = (int) $expediteur['solde'];

        if ($soldeAvantExp < $total) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Solde insuffisant.']);
        }

        $soldeApresExp = $soldeAvantExp - (int) $total;
        $this->clientModel->update($clientId, ['solde' => $soldeApresExp]);

        // Mise à jour du solde du destinataire si transfert interne
        if ($clientDestinataireId !== null) {
            $soldeAvantDest = (int) $destinataire['solde'];
            $soldeApresDest = $soldeAvantDest + (int) $montant;
            $this->clientModel->update($clientDestinataireId, ['solde' => $soldeApresDest]);
        }

        $transactionId = $this->transactionModel->insert([
            'client_id'                  => $clientId,
            'type_operation_id'          => $typeId,
            'montant'                    => $montant,
            'frais'                      => $fraisTransfert,
            'client_destinataire_id'     => $clientDestinataireId ?? null,
            'destinataire_externe_numero' => $destinataireExterneNumero,
            'destinataire_externe_code'   => $destinataireExterneCode,
            'withdraw_fee_paid'          => $withdrawFeePaid,
        ], true);

        $this->majSoldeModel->insert([
            'transaction_id' => $transactionId,
            'client_id'      => $clientId,
            'solde_avant'    => $soldeAvantExp,
            'solde_apres'    => $soldeApresExp,
        ]);

        $db->transComplete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transfert effectué.',
            'solde'   => $soldeApresExp,
        ]);
    }

    /**
     * Effectue des transferts multiples vers plusieurs destinataires.
     * Le montant est divisé équitablement entre tous les destinataires.
     * Tous les destinataires doivent appartenir au même opérateur (MVola seulement).
     * L'opération est atomique : succès ou annulation complète.
     */
    public function bulkTransfert()
    {
        $clientId = (int) $this->request->getPost('client_id');
        $montantTotal = (float) $this->request->getPost('montant_total');
        $numerosList = $this->request->getPost('numeros'); // JSON string or array
        
        // Parse numeros
        if (is_string($numerosList)) {
            $numerosList = json_decode($numerosList, true);
        }
        if (!is_array($numerosList)) {
            $numerosList = [];
        }
        $numerosList = array_map('trim', $numerosList);
        $numerosList = array_filter($numerosList);

        // Validation basique
        if ($montantTotal <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Montant invalide.']);
        }

        if (count($numerosList) < 2) {
            return $this->response->setJSON(['success' => false, 'message' => 'Au moins 2 destinataires sont requis.']);
        }

        $numDestinaires = count($numerosList);

        $expediteur = $this->clientModel->find($clientId);
        if ($expediteur === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client introuvable.']);
        }

        // Vérifications : tous les numéros doivent être valides, internes, différents
        $numerosList = array_unique($numerosList);
        if (count($numerosList) !== $numDestinaires) {
            return $this->response->setJSON(['success' => false, 'message' => 'Il y a des doublons dans la liste.']);
        }

        $destinataires = [];
        foreach ($numerosList as $numero) {
            // Vérifier que le numéro n'est pas celui de l'expéditeur
            if ($numero === $expediteur['numero_telephone']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Impossible de se transférer à soi-même.']);
            }

            // Vérifier que le numéro est un numéro MVola (opérateur interne)
            if ($this->prefixeModel->getOperateurExterne($numero) !== null) {
                return $this->response->setJSON(['success' => false, 'message' => 'Tous les destinataires doivent être des numéros MVola.']);
            }

            // Chercher le destinataire
            $destinataire = $this->clientModel->findByNumero($numero);
            if ($destinataire === null) {
                return $this->response->setJSON(['success' => false, 'message' => "Le numéro $numero n'existe pas."]);
            }

            $destinataires[] = $destinataire;
        }

        // Calculer le montant par destinataire
        // Stratégie : répartir le montant total équitablement
        // Si le montant n'est pas divisible exactement, donner le reste au premier destinataire
        $montantParDestinataire = (int) floor($montantTotal / count($destinataires));
        $reste = $montantTotal - ($montantParDestinataire * count($destinataires));

        // Calculer les frais totaux du transfert (basés sur le montant total)
        $typeId = $this->typeOperationModel->getIdByNom('transfert');
        $fraisTransfert = $this->fraisService->calculerFrais($typeId, $montantTotal);

        $totalDebite = $montantTotal + $fraisTransfert;
        $soldeAvantExp = (int) $expediteur['solde'];

        if ($soldeAvantExp < $totalDebite) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solde insuffisant.']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $bulkId = time() . '-' . $clientId; // Identifiant unique pour ce groupe de transferts
            $soldeApresExp = $soldeAvantExp - (int) $totalDebite;
            $this->clientModel->update($clientId, ['solde' => $soldeApresExp]);

            $montantsParDestinataire = [];
            foreach ($destinataires as $idx => $destinataire) {
                $montantsParDestinataire[$idx] = $montantParDestinataire + ($idx === 0 ? $reste : 0);
            }

            // Enregistrer chaque transfert
            foreach ($destinataires as $idx => $destinataire) {
                $montantDestinataire = $montantsParDestinataire[$idx];
                $soldeAvantDest = (int) $destinataire['solde'];
                $soldeApresDest = $soldeAvantDest + $montantDestinataire;

                // Mettre à jour le solde du destinataire
                $this->clientModel->update((int) $destinataire['id'], ['solde' => $soldeApresDest]);

                // Enregistrer la transaction
                // Les frais sont répartis proportionnellement ou assignés au premier transfert
                $fraisTransaction = ($idx === 0) ? $fraisTransfert : 0;

                $transactionId = $this->transactionModel->insert([
                    'client_id'             => $clientId,
                    'type_operation_id'     => $typeId,
                    'montant'               => $montantDestinataire,
                    'frais'                 => $fraisTransaction,
                    'client_destinataire_id' => (int) $destinataire['id'],
                    'bulk_transfer_id'      => $bulkId,
                ], true);

                $this->majSoldeModel->insert([
                    'transaction_id' => $transactionId,
                    'client_id'      => (int) $destinataire['id'],
                    'solde_avant'    => $soldeAvantDest,
                    'solde_apres'    => $soldeApresDest,
                ]);
            }

            // Enregistrer la transaction du débit de l'expéditeur
            $transactionExpId = $this->transactionModel->insert([
                'client_id'         => $clientId,
                'type_operation_id' => $typeId,
                'montant'           => $montantTotal,
                'frais'             => $fraisTransfert,
                'bulk_transfer_id'  => $bulkId,
            ], true);

            $this->majSoldeModel->insert([
                'transaction_id' => $transactionExpId,
                'client_id'      => $clientId,
                'solde_avant'    => $soldeAvantExp,
                'solde_apres'    => $soldeApresExp,
            ]);

            $db->transComplete();

            return $this->response->setJSON([
                'success' => true,
                'message' => "Transferts multiples effectués vers " . count($destinataires) . " destinataires.",
                'solde'   => $soldeApresExp,
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors du transfert : ' . $e->getMessage()]);
        }
    }
}
