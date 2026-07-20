<?php

namespace App\Controllers;

use App\Libraries\FraisService;
use App\Models\ClientModel;
use App\Models\MajSoldeModel;
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
    protected FraisService $fraisService;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel        = new ClientModel();
        $this->transactionModel   = new TransactionModel();
        $this->majSoldeModel      = new MajSoldeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->fraisService       = new FraisService();
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
        $frais  = $this->fraisService->calculerFrais($typeId, $montant);
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
     * Effectue un transfert vers un autre client (identifié par son numéro).
     * Les frais sont à la charge de l'expéditeur.
     */
    public function transfert()
    {
        $clientId         = (int) $this->request->getPost('client_id');
        $numeroDestinataire = trim((string) $this->request->getPost('numero_destinataire'));
        $montant           = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Montant invalide.']);
        }

        $expediteur = $this->clientModel->find($clientId);
        if ($expediteur === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client introuvable.']);
        }

        $destinataire = $this->clientModel->findByNumero($numeroDestinataire);
        if ($destinataire === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Destinataire introuvable.']);
        }

        if ((int) $destinataire['id'] === $clientId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Impossible de se transférer à soi-même.']);
        }

        $typeId = $this->typeOperationModel->getIdByNom('transfert');
        $frais  = $this->fraisService->calculerFrais($typeId, $montant);
        $total  = $montant + $frais;

        $soldeAvantExp = (int) $expediteur['solde'];

        if ($soldeAvantExp < $total) {
            return $this->response->setJSON(['success' => false, 'message' => 'Solde insuffisant.']);
        }

        $soldeApresExp  = $soldeAvantExp - (int) $total;
        $soldeAvantDest = (int) $destinataire['solde'];
        $soldeApresDest = $soldeAvantDest + (int) $montant;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->clientModel->update($clientId, ['solde' => $soldeApresExp]);
        $this->clientModel->update($destinataire['id'], ['solde' => $soldeApresDest]);

        $transactionId = $this->transactionModel->insert([
            'client_id'               => $clientId,
            'type_operation_id'       => $typeId,
            'montant'                 => $montant,
            'frais'                   => $frais,
            'client_destinataire_id'  => $destinataire['id'],
        ], true);

        $this->majSoldeModel->insert([
            'transaction_id' => $transactionId,
            'client_id'      => $clientId,
            'solde_avant'    => $soldeAvantExp,
            'solde_apres'    => $soldeApresExp,
        ]);

        $this->majSoldeModel->insert([
            'transaction_id' => $transactionId,
            'client_id'      => $destinataire['id'],
            'solde_avant'    => $soldeAvantDest,
            'solde_apres'    => $soldeApresDest,
        ]);

        $db->transComplete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transfert effectué.',
            'solde'   => $soldeApresExp,
        ]);
    }
}
