<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigurationModel extends Model
{
    protected $table         = 'configuration';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['cle', 'valeur'];

    protected $validationRules = [
        'cle'    => 'required|max_length[100]',
        'valeur' => 'required|max_length[255]',
    ];

    public function getCommissionExterne(): int
    {
        $row = $this->where('cle', 'commission_externe')->first();

        return $row !== null ? (int) $row['valeur'] : 20;
    }

    public function setCommissionExterne(int $pourcentage): bool
    {
        return $this->update(1, ['valeur' => (string) $pourcentage]) || $this->insert(['cle' => 'commission_externe', 'valeur' => (string) $pourcentage]);
    }
}