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

    /**
     * Vérifie si un numéro de téléphone commence par un préfixe valide.
     */
    public function estPrefixeValide(string $numero): bool
    {
        $prefixe = substr($numero, 0, 3);

        return $this->where('prefixe', $prefixe)->countAllResults() > 0;
    }
}
