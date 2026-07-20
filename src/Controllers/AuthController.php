<?php
namespace App\Controllers;
use App\Models\ClientModel;
use CodeIgniter\Controller;
use Config\Services;

class AuthController extends Controller
{
    public function index()
    {
        echo view('login');
    }

    public function login()
    {
        $phone = $this->request->getPost('phone_number');
        $prefix = substr($phone, 0, 3);

        // Vérifier format/longueur téléphone
        if (!preg_match('/^\d{9,10}$/', $phone)) {
            return redirect()->back()->with('error', 'Numéro invalide');
        }

        // Vérifier préfixe dans la table prefixes
        $db = db_connect();
        $prefixRow = $db->table('prefixes')->where('code', $prefix)->get()->getRow();
        if (!$prefixRow) {
            return redirect()->back()->with('error', 'Préfixe non autorisé');
        }

        $clientModel = new ClientModel();
        $client = $clientModel->where('phone_number', $phone)->first();

        if (!$client) {
            $id = $clientModel->insert([
                'phone_number' => $phone,
                'solde' => 0
            ]);
            $client = $clientModel->find($id);
        }

        // Enregistrer en session (par exemple l'id client)
        session()->set('client_id', $client['id']);
        session()->set('phone_number', $client['phone_number']);
        // Redirige vers page de solde ou dashboard
        return redirect()->to('/dashboard');
    }
}