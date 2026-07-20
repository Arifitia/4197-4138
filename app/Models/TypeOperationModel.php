<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table         = 'types_operations';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['nom'];

    protected $validationRules = [
        'nom' => 'required|is_unique[types_operations.nom,id,{id}]|min_length[2]|max_length[50]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required'   => 'Le nom du type d\'opération est obligatoire.',
            'is_unique'  => 'Ce type d\'opération existe déjà.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
        ],
    ];

    /**
     * Retourne l'id du type d'opération à partir de son nom
     * ('depot', 'retrait' ou 'transfert').
     */
    public function getIdByNom(string $nom): ?int
    {
        $row = $this->where('nom', $nom)->first();

        return $row['id'] ?? null;
    }
}
