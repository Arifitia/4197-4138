<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'prefixes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['prefixe'];

    protected $validationRules = [
        'prefixe' => 'required|is_unique[prefixes.prefixe,id,{id}]|exact_length[3]|numeric',
    ];

    protected $validationMessages = [
        'prefixe' => [
            'required'     => 'Le préfixe est obligatoire.',
            'is_unique'    => 'Ce préfixe existe déjà.',
            'exact_length' => 'Le préfixe doit contenir exactement 3 chiffres.',
            'numeric'      => 'Le préfixe ne doit contenir que des chiffres.',
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

        return in_array($prefixe, ['034', '038'], true);
    }

    public function getOperateurExterne(string $numero): ?string
    {
        $prefixe = substr($numero, 0, 3);

        return match ($prefixe) {
            '033', '035' => 'AIRTEL',
            '032', '037' => 'ORANGE',
            default => null,
        };
    }
}
