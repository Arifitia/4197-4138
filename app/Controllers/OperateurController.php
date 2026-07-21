<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\GainModel;
use App\Models\TransactionModel;

class OperateurController extends BaseController
{
    protected ClientModel $clientModel;
    protected GainModel $gainModel;
    protected TransactionModel $transactionModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel      = new ClientModel();
        $this->gainModel        = new GainModel();
        $this->transactionModel = new TransactionModel();
    }

    public function dashboard()
    {
        if (session()->get('operateur_role') !== 'operateur') {
            return redirect()->to('/operateur/auth')->with('error', 'Accès réservé aux opérateurs.');
        }

        $totalClients = $this->clientModel->countAllResults();
        $totalTransactions = $this->transactionModel->countAllResults();

        $gains = $this->gainModel->getTotalGains();

        return view('operateur/dashboard', [
            'operateur_nom'      => session('operateur_nom'),
            'totalClients'       => $totalClients,
            'totalTransactions'  => $totalTransactions,
            'totalGains'         => $gains['total'],
            'gainsRetraits'      => $gains['retraits'],
            'gainsInternes'      => $gains['internes'],
            'gainsExternes'      => $gains['externes'],
        ]);
    }

    public function clients()
    {
        if (session()->get('operateur_role') !== 'operateur') {
            return redirect()->to('/operateur/auth')->with('error', 'Accès réservé aux opérateurs.');
        }

        $data = [
            'clients' => $this->clientModel->orderBy('numero_telephone', 'ASC')->findAll(),
        ];

        return view('operateur/clients', $data);
    }

    public function gains()
    {
        if (session()->get('operateur_role') !== 'operateur') {
            return redirect()->to('/operateur/auth')->with('error', 'Accès réservé aux opérateurs.');
        }

        $gains = $this->gainModel->situationGains();
        $totals = $this->gainModel->getTotalGains();
        $montantsDus = $this->gainModel->getMontantsDusParOperateur();

        return view('operateur/gains', [
            'lignes'             => $gains['lignes'],
            'total'              => $gains['total'],
            'total_retraits'     => $totals['retraits'],
            'total_internes'     => $totals['internes'],
            'total_externes'     => $totals['externes'],
            'total_gains'        => $totals['total'],
            'montants_dus'       => $montantsDus,
            'operateur_nom'      => session('operateur_nom'),
        ]);
    }
}
