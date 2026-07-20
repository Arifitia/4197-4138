<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\TypeOperationModel;

class BaremeController extends BaseController
{
    protected BaremeModel $baremeModel;
    protected TypeOperationModel $typeOperationModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->baremeModel        = new BaremeModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    /**
     * Affiche les barèmes, groupés par type d'opération.
     */
    public function index()
    {
        $data = [
            'baremes' => $this->baremeModel->listeAvecTypeOperation(),
        ];

        return view('baremes/index', $data);
    }

    public function create()
    {
        return view('baremes/form', [
            'bareme' => null,
            'types'  => $this->typeOperationModel->findAll(),
        ]);
    }

    public function store()
    {
        $data = [
            'type_operation_id' => (int) $this->request->getPost('type_operation_id'),
            'montant_min'       => (int) $this->request->getPost('montant_min'),
            'montant_max'       => (int) $this->request->getPost('montant_max'),
            'frais'             => (int) $this->request->getPost('frais'),
        ];

        if (! $this->baremeModel->save($data)) {
            return view('baremes/form', [
                'bareme' => $data,
                'types'  => $this->typeOperationModel->findAll(),
                'errors' => $this->baremeModel->errors(),
            ]);
        }

        return redirect()->to('/baremes')->with('success', 'Tranche ajoutée.');
    }

    public function edit(int $id)
    {
        $bareme = $this->baremeModel->find($id);

        if ($bareme === null) {
            return redirect()->to('/baremes')->with('error', 'Barème introuvable.');
        }

        return view('baremes/form', [
            'bareme' => $bareme,
            'types'  => $this->typeOperationModel->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $data = [
            'type_operation_id' => (int) $this->request->getPost('type_operation_id'),
            'montant_min'       => (int) $this->request->getPost('montant_min'),
            'montant_max'       => (int) $this->request->getPost('montant_max'),
            'frais'             => (int) $this->request->getPost('frais'),
        ];

        if (! $this->baremeModel->update($id, $data)) {
            return view('baremes/form', [
                'bareme' => array_merge(['id' => $id], $data),
                'types'  => $this->typeOperationModel->findAll(),
                'errors' => $this->baremeModel->errors(),
            ]);
        }

        return redirect()->to('/baremes')->with('success', 'Tranche modifiée.');
    }

    public function delete(int $id)
    {
        $this->baremeModel->delete($id);

        return redirect()->to('/baremes')->with('success', 'Tranche supprimée.');
    }
}
