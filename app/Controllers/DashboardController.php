<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;

/**
 * Tableau de bord du client connecté : informations du compte, solde
 * et historique des opérations (dépôts, retraits, transferts).
 */
class DashboardController extends BaseController
{
    protected ClientModel $clientModel;
    protected TransactionModel $transactionModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel      = new ClientModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $clientId = session()->get('client_id');

        if (! $clientId) {
            return redirect()->to('/auth')->with('error', 'Veuillez vous connecter pour accéder à votre tableau de bord.');
        }

        $client = $this->clientModel->find($clientId);

        if ($client === null) {
            // Le compte n'existe plus : on nettoie la session.
            session()->destroy();

            return redirect()->to('/auth')->with('error', 'Votre compte est introuvable, veuillez vous reconnecter.');
        }

        $historique = $this->transactionModel->historiqueClient($client['id']);

        return view('dashboard', [
            'client'     => $client,
            'historique' => $historique,
        ]);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/auth')->with('success', 'Vous avez été déconnecté.');
    }
}
