<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixeModel;

/**
 * Authentification client par numéro de téléphone.
 * Pas d'inscription classique : si le numéro n'existe pas encore
 * (et que son préfixe est valide), un compte client est créé automatiquement.
 */
class AuthController extends BaseController
{
    protected ClientModel $clientModel;
    protected PrefixeModel $prefixeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel  = new ClientModel();
        $this->prefixeModel = new PrefixeModel();
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function index()
    {
        // Si déjà connecté, on va directement au tableau de bord.
        if (session()->get('client_id')) {
            return redirect()->to('/dashboard');
        }

        return view('login');
    }

    /**
     * Traite la connexion (ou création automatique) du client.
     */
    public function login()
    {
        $numero = trim((string) $this->request->getPost('numero_telephone'));

        // Validation basique du format du numéro.
        if ($numero === '' || ! preg_match('/^\d{10}$/', $numero)) {
            return redirect()->to('/auth')->with('error', 'Veuillez saisir un numéro de téléphone valide (10 chiffres).');
        }

        // Vérification que le préfixe opérateur (3 premiers chiffres) est autorisé.
        if (! $this->prefixeModel->estPrefixeValide($numero)) {
            return redirect()->to('/auth')->with('error', 'Ce préfixe opérateur n\'est pas reconnu.');
        }

        // Recherche du client, création automatique si besoin.
        $client = $this->clientModel->findOrCreate($numero);

        session()->set([
            'client_id'     => $client['id'],
            'client_numero' => $client['numero_telephone'],
        ]);

        return redirect()->to('/dashboard')->with('success', 'Connexion réussie. Bienvenue !');
    }
}
