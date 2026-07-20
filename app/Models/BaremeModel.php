<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table         = 'baremes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];

    protected $validationRules = [
        'type_operation_id' => 'required|integer',
        'montant_min'       => 'required|integer|greater_than[0]',
        'montant_max'       => 'required|integer|greater_than_equal_to[montant_min]',
        'frais'             => 'required|integer|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'montant_max' => [
            'greater_than_equal_to' => 'Le montant max doit être supérieur ou égal au montant min.',
        ],
    ];

    /**
     * Liste des barèmes avec le nom du type d'opération, pour l'affichage.
     */
    public function listeAvecTypeOperation(): array
    {
        return $this->select('baremes.*, types_operations.nom AS type_operation_nom')
            ->join('types_operations', 'types_operations.id = baremes.type_operation_id')
            ->orderBy('baremes.type_operation_id', 'ASC')
            ->orderBy('baremes.montant_min', 'ASC')
            ->findAll();
    }

    /**
     * Trouve le barème (donc les frais) correspondant à un montant
     * pour un type d'opération donné.
     */
    public function trouverBareme(int $typeOperationId, float $montant): ?array
    {
        return $this->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();
    }
}
