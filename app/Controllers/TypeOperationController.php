<?php

namespace App\Controllers;

use App\Models\TypeOperationModel;

/**
 * CRUD des types d'opérations (dépôt, retrait, transfert, ...) côté opérateur.
 */
class TypeOperationController extends BaseController
{
    protected TypeOperationModel $typeOperationModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->typeOperationModel = new TypeOperationModel();
    }

    /**
     * Affiche la liste des types d'opérations.
     */
    public function index()
    {
        $data = [
            'types' => $this->typeOperationModel->orderBy('id', 'ASC')->findAll(),
        ];

        return view('types_operations/index', $data);
    }

    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        return view('types_operations/form', ['type' => null]);
    }

    /**
     * Enregistre un nouveau type d'opération.
     */
    public function store()
    {
        $data = ['nom' => trim((string) $this->request->getPost('nom'))];

        if (! $this->typeOperationModel->save($data)) {
            return view('types_operations/form', [
                'type'   => $data,
                'errors' => $this->typeOperationModel->errors(),
            ]);
        }

        return redirect()->to('/types-operations')->with('success', 'Type d\'opération ajouté.');
    }

    /**
     * Formulaire de modification.
     */
    public function edit(int $id)
    {
        $type = $this->typeOperationModel->find($id);

        if ($type === null) {
            return redirect()->to('/types-operations')->with('error', 'Type d\'opération introuvable.');
        }

        return view('types_operations/form', ['type' => $type]);
    }

    /**
     * Applique la modification.
     */
    public function update(int $id)
    {
        $data = ['nom' => trim((string) $this->request->getPost('nom'))];

        if (! $this->typeOperationModel->update($id, $data)) {
            return view('types_operations/form', [
                'type'   => array_merge(['id' => $id], $data),
                'errors' => $this->typeOperationModel->errors(),
            ]);
        }

        return redirect()->to('/types-operations')->with('success', 'Type d\'opération modifié.');
    }

    /**
     * Supprime un type d'opération.
     */
    public function delete(int $id)
    {
        $this->typeOperationModel->delete($id);

        return redirect()->to('/types-operations')->with('success', 'Type d\'opération supprimé.');
    }
}
