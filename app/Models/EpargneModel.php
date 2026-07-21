<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table         = 'epargne';
    protected $primaryKey    = 'id_epargne';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['id_client', 'pourcentage'];

    protected $validationRules = [
        'cle'    => 'required|max_length[100]',
        'valeur' => 'required|max_length[255]',
    ];

    public function getEpargneCLient(): int
    {
        $row = $this->where('id_client')->first();

        return $row !== null ? (int) $row['valeur'] : 20;
    }

    public function setCommissionExterne(int $pourcentage): bool
    {
        return $this->update(1, ['valeur' => (string) $pourcentage]) || $this->insert(['id_client' => '', 'valeur' => (string) $pourcentage]);
    }
}