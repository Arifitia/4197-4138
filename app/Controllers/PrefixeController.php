<?php

namespace App\Controllers;

use App\Models\PrefixeModel;

class PrefixeController extends BaseController
{
    protected PrefixeModel $prefixeModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->prefixeModel = new PrefixeModel();
    }

    /**
     * Affiche la liste des préfixes.
     */
    public function index()
    {
        $data = [
            'prefixes' => $this->prefixeModel->orderBy('prefixe', 'ASC')->findAll(),
        ];

        return view('prefixes/index', $data);
    }

    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        return view('prefixes/form', ['prefixe' => null]);
    }

    /**
     * Enregistre un nouveau préfixe.
     */
    public function store()
    {
        $data = ['prefixe' => trim((string) $this->request->getPost('prefixe'))];

        if (! $this->prefixeModel->save($data)) {
            return view('prefixes/form', [
                'prefixe' => $data,
                'errors'  => $this->prefixeModel->errors(),
            ]);
        }

        return redirect()->to('/prefixes')->with('success', 'Préfixe ajouté.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(int $id)
    {
        $prefixe = $this->prefixeModel->find($id);

        if ($prefixe === null) {
            return redirect()->to('/prefixes')->with('error', 'Préfixe introuvable.');
        }

        return view('prefixes/form', ['prefixe' => $prefixe]);
    }

    /**
     * Applique la modification d'un préfixe.
     */
    public function update(int $id)
    {
        $data = ['prefixe' => trim((string) $this->request->getPost('prefixe'))];

        if (! $this->prefixeModel->update($id, $data)) {
            return view('prefixes/form', [
                'prefixe' => array_merge(['id' => $id], $data),
                'errors'  => $this->prefixeModel->errors(),
            ]);
        }

        return redirect()->to('/prefixes')->with('success', 'Préfixe modifié.');
    }

    /**
     * Supprime un préfixe.
     */
    public function delete(int $id)
    {
        $this->prefixeModel->delete($id);

        return redirect()->to('/prefixes')->with('success', 'Préfixe supprimé.');
    }
}
