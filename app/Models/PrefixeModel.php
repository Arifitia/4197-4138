<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'prefixes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['prefixe', 'operateur_code', 'type'];

    protected $validationRules = [
        'prefixe'        => 'required|is_unique[prefixes.prefixe,id,{id}]|exact_length[3]|numeric',
        'operateur_code' => 'required|in_list[MVOLA,AIRTEL,ORANGE]',
        'type'           => 'required|in_list[interne,externe]',
    ];

    protected $validationMessages = [
        'prefixe' => [
            'required'     => 'Le préfixe est obligatoire.',
            'is_unique'    => 'Ce préfixe existe déjà.',
            'exact_length' => 'Le préfixe doit contenir exactement 3 chiffres.',
            'numeric'      => 'Le préfixe ne doit contenir que des chiffres.',
        ],
        'operateur_code' => [
            'required' => 'Le code opérateur est obligatoire.',
            'in_list'  => 'Code opérateur invalide.',
        ],
        'type' => [
            'required' => 'Le type est obligatoire.',
            'in_list'  => 'Type invalide.',
        ],
    ];

    public function estPrefixeValide(string $numero): bool
    {
        $prefixe = substr($numero, 0, 3);

        return $this->where('prefixe', $prefixe)->countAllResults() > 0;
    }

    public function estPrefixeMVola(string $numero): bool
    {
        $prefixe = substr($numero, 0, 3);

        return $this->where('prefixe', $prefixe)
            ->where('operateur_code', 'MVOLA')
            ->countAllResults() > 0;
    }

    public function getOperateurExterne(string $numero): ?string
    {
        $prefixe = substr($numero, 0, 3);

        $row = $this->where('prefixe', $prefixe)
            ->where('type', 'externe')
            ->first();

        return $row ? $row['operateur_code'] : null;
    }

    public function getOperateurParNumero(string $numero): ?array
    {
        $prefixe = substr($numero, 0, 3);

        return $this->select('operateur_code, type')
            ->where('prefixe', $prefixe)
            ->first();
    }

    public function estPrefixeMVolaParId(int $id): bool
    {
        $prefixe = $this->find($id);

        return $prefixe !== null && $prefixe['operateur_code'] === 'MVOLA';
    }
}
