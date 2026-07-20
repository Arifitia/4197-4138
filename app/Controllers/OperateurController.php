<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\GainModel;

/**
 * Vues de synthèse pour l'opérateur : situation des comptes clients
 * (liste, numéros, soldes) et situation des gains (frais collectés).
 */
class OperateurController extends BaseController
{
    protected ClientModel $clientModel;
    protected GainModel $gainModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel = new ClientModel();
        $this->gainModel   = new GainModel();
    }

    /**
     * Affiche la liste des clients avec leurs numéros et soldes.
     */
    public function clients()
    {
        $data = [
            'clients' => $this->clientModel->orderBy('numero_telephone', 'ASC')->findAll(),
        ];

        return view('operateur/clients', $data);
    }

    /**
     * Affiche la situation des gains de l'opérateur (retraits + transferts).
     */
    public function gains()
    {
        return view('operateur/gains', $this->gainModel->situationGains());
    }
}
