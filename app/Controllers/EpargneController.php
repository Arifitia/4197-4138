<?php

namespace App\Controllers;

use App\Models\EpargneModel;

class EpargneController extends BaseController
{
    protected EpargneModel $EpargneModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->EpargneModel = new EpargneModel();
    }

    public function index()
    {
        $clientId = session()->get('client_id');

        if (! $clientId) {
            return redirect()->to('/auth')->with('error', 'Veuillez vous connecter pour accéder à votre historique.');
        }


        $epargne = $this->EpargneModel->getCommissionExterne();

        return view('operateur/Epargne', [
            'epargne' => $epargne,
        ]);
    }

    public function update()
    {
        if (session()->get('operateur_role') !== 'operateur') {
            return redirect()->to('/operateur/auth')->with('error', 'Accès réservé aux opérateurs.');
        }

        $pourcentage = (int) $this->request->getPost('commission_externe');

        if ($pourcentage < 0 || $pourcentage > 100) {
            return redirect()->to('/operateur/Epargne')->with('error', 'Le pourcentage doit être compris entre 0 et 100.');
        }

        $this->EpargneModel->setCommissionExterne($pourcentage);

        return redirect()->to('/operateur/Epargne')->with('success', 'Epargne mise à jour.');
    }
}