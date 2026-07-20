<?php

namespace App\Controllers;

/**
 * Connexion à l'espace opérateur MVola.
 * Pour cette version, la connexion est directe sans mot de passe.
 * La session opérateur contient un rôle simple pour différencier
 * l'espace client de l'espace opérateur.
 */
class OperateurAuthController extends BaseController
{
    public function index()
    {
        if (session()->get('operateur_role') === 'operateur') {
            return redirect()->to('/operateur/dashboard');
        }

        return view('operateur/login_simple');
    }

    public function login()
    {
        session()->set([
            'operateur_role' => 'operateur',
            'operateur_nom'  => 'MVola',
        ]);

        return redirect()->to('/operateur/dashboard')->with('success', 'Connexion opérateur réussie.');
    }

    public function logout()
    {
        session()->remove('operateur_role');
        session()->remove('operateur_nom');

        return redirect()->to('/operateur/auth')->with('success', 'Déconnexion opérateur réussie.');
    }
}