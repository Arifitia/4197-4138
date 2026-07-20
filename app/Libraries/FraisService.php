<?php

namespace App\Libraries;

use App\Models\BaremeModel;

/**
 * Calcule les frais applicables à une opération selon le barème en vigueur.
 */
class FraisService
{
    protected BaremeModel $baremeModel;

    public function __construct()
    {
        $this->baremeModel = new BaremeModel();
    }

    /**
     * Retourne le montant des frais pour un type d'opération et un montant donnés.
     * Si aucune tranche ne correspond (ex: dépôt, qui n'a pas de barème), retourne 0.
     */
    public function calculerFrais(int $typeOperationId, float $montant): int
    {
        $bareme = $this->baremeModel->trouverBareme($typeOperationId, $montant);

        return $bareme['frais'] ?? 0;
    }
}
