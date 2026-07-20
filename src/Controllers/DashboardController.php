<?php
namespace App\Controllers;
use App\Models\ClientModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $clientId = session('client_id');
        if (!$clientId) {
            return redirect()->to('/auth');
        }

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            return redirect()->to('/auth');
        }

        return view('dashboard', ['client' => $client]);
    }

    public function logout()
    {
        session()->remove('client_id');
        session()->remove('phone_number');
        return redirect()->to('/auth');
    }
}