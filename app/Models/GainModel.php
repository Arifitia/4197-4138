<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modèle en lecture seule s'appuyant sur la vue SQL `vue_situation_gains`
 * (gains de l'opérateur générés par les frais de retrait et de transfert).
 */
class GainModel extends Model
{
    protected $table         = 'vue_situation_gains';
    protected $primaryKey    = 'type_operation';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [];

    /**
     * Retourne le détail des gains (par type d'opération) ainsi que le total.
     */
    public function situationGains(): array
    {
        $lignes = $this->findAll();
        $total  = 0;

        foreach ($lignes as $ligne) {
            $total += (int) $ligne['total_frais'];
        }

        return [
            'lignes' => $lignes,
            'total'  => $total,
        ];
    }
}
