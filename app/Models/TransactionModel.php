<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'transactions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'client_id',
        'type_operation_id',
        'montant',
        'frais',
        'client_destinataire_id',
        'date_transaction',
    ];

    /**
     * Historique des opérations d'un client, du plus récent au plus ancien,
     * avec le nom du type d'opération et le numéro du destinataire (si transfert).
     */
    public function historiqueClient(int $clientId): array
    {
        return $this->select('
                transactions.*,
                types_operations.nom AS type_operation_nom,
                dest.numero_telephone AS destinataire_numero
            ')
            ->join('types_operations', 'types_operations.id = transactions.type_operation_id')
            ->join('clients AS dest', 'dest.id = transactions.client_destinataire_id', 'left')
            ->where('transactions.client_id', $clientId)
            ->orderBy('transactions.date_transaction', 'DESC')
            ->findAll();
    }
}
