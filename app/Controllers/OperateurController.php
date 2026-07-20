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

        return view('operateur/dashboard', [
            'operateur_nom'      => session('operateur_nom'),
            'totalClients'       => $totalClients,
            'totalTransactions'  => $totalTransactions,
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

        $gainsRetraits = $this->gainModel->getGainsRetraits();
        $gainsTransfertsInternes = $this->gainModel->getGainsTransfertsInternes();
        $gainsTransfertsExternes = $this->gainModel->getGainsTransfertsExternes();
        $montantsDus = $this->gainModel->getMontantsDusParOperateur();

        $totalRetraits = (int) ($gainsRetraits['total_frais'] ?? 0);
        $totalInternes = (int) ($gainsTransfertsInternes['total_frais'] ?? 0);
        $totalExternes = (int) ($gainsTransfertsExternes['total_frais'] ?? 0);
        $totalGains = $totalRetraits + $totalInternes + $totalExternes;

        return view('operateur/gains', [
            'lignes'             => $gains['lignes'],
            'total'              => $gains['total'],
            'total_retraits'     => $totalRetraits,
            'total_internes'     => $totalInternes,
            'total_externes'     => $totalExternes,
            'total_gains'        => $totalGains,
            'montants_dus'       => $montantsDus,
            'operateur_nom'      => session('operateur_nom'),
        ]);
    }
}
