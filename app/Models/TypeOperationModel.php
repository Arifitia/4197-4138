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
