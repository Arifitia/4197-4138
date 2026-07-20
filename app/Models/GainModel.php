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

    public function getGainsRetraits(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT SUM(tr.frais) AS total_frais
            FROM transactions tr
            JOIN types_operations t ON t.id = tr.type_operation_id
            WHERE t.nom = 'retrait'
        ")->getRowArray();
    }

    public function getGainsTransfertsInternes(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT SUM(tr.frais) AS total_frais
            FROM transactions tr
            JOIN types_operations t ON t.id = tr.type_operation_id
            WHERE t.nom = 'transfert'
              AND tr.destinataire_externe_code IS NULL
        ")->getRowArray();
    }

    public function getGainsTransfertsExternes(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT SUM(tr.frais) AS total_frais
            FROM transactions tr
            JOIN types_operations t ON t.id = tr.type_operation_id
            WHERE t.nom = 'transfert'
              AND tr.destinataire_externe_code IS NOT NULL
        ")->getRowArray();
    }

    public function getMontantsDusParOperateur(): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT
                tr.destinataire_externe_code AS operateur_externe,
                COUNT(tr.id) AS nombre_operations,
                SUM(tr.montant) AS total_montant
            FROM transactions tr
            WHERE tr.destinataire_externe_code IS NOT NULL
            GROUP BY tr.destinataire_externe_code
        ")->getResultArray();
    }
}
