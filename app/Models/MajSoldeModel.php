<?php

namespace App\Models;

use CodeIgniter\Model;

class MajSoldeModel extends Model
{
    protected $table         = 'maj_solde';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['transaction_id', 'client_id', 'solde_avant', 'solde_apres'];
}
