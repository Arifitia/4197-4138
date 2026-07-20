<?php

namespace App\Libraries;

use App\Models\BaremeModel;
use App\Models\ConfigurationModel;

/**
 * Calcule les frais applicables à une opération selon le barème en vigueur.
 */
class FraisService
{
    protected BaremeModel $baremeModel;
    protected ConfigurationModel $configurationModel;

    public function __construct()
    {
        $this->baremeModel = new BaremeModel();
        $this->configurationModel = new ConfigurationModel();
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

    /**
     * Retourne le montant des frais pour un transfert, avec commission externe si nécessaire.
     *
     * @param int $typeOperationId
     * @param float $montant
     * @param bool $estExterne
     * @return int
     */
    public function calculerFraisTransfert(int $typeOperationId, float $montant, bool $estExterne = false): int
    {
        $fraisNormal = $this->calculerFrais($typeOperationId, $montant);

        if (! $estExterne) {
            return $fraisNormal;
        }

        $commissionExterne = $this->configurationModel->getCommissionExterne();
        $commission = (int) round($fraisNormal * $commissionExterne / 100);

        return $fraisNormal + $commission;
    }
}
