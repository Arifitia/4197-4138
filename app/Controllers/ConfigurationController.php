<?php

namespace App\Controllers;

use App\Models\ConfigurationModel;

class ConfigurationController extends BaseController
{
    protected ConfigurationModel $configurationModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->configurationModel = new ConfigurationModel();
    }

    public function index()
    {
        if (session()->get('operateur_role') !== 'operateur') {
            return redirect()->to('/operateur/auth')->with('error', 'Accès réservé aux opérateurs.');
        }

        $commissionExterne = $this->configurationModel->getCommissionExterne();

        return view('operateur/configuration', [
            'commission_externe' => $commissionExterne,
        ]);
    }

    public function update()
    {
        if (session()->get('operateur_role') !== 'operateur') {
            return redirect()->to('/operateur/auth')->with('error', 'Accès réservé aux opérateurs.');
        }

        $pourcentage = (int) $this->request->getPost('commission_externe');

        if ($pourcentage < 0 || $pourcentage > 100) {
            return redirect()->to('/operateur/configuration')->with('error', 'Le pourcentage doit être compris entre 0 et 100.');
        }

        $this->configurationModel->setCommissionExterne($pourcentage);

        return redirect()->to('/operateur/configuration')->with('success', 'Configuration mise à jour.');
    }
}