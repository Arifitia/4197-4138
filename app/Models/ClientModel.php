<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = false;
    protected $allowedFields    = ['numero_telephone', 'solde'];

    /**
     * Retrouve un client par son numéro de téléphone.
     */
    public function findByNumero(string $numero): ?array
    {
        return $this->where('numero_telephone', $numero)->first();
    }

    /**
     * Crée un client s'il n'existe pas encore pour ce numéro MVola, sinon le retourne.
     */
    public function findOrCreate(string $numero): array
    {
        $client = $this->findByNumero($numero);

        if ($client === null) {
            $id = $this->insert(['numero_telephone' => $numero, 'solde' => 0], true);
            $client = $this->find($id);
        }

        return $client;
    }
}
