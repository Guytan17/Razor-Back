<?php

namespace App\Models;

use CodeIgniter\Model;

class SponsorRankModel extends Model
{
    protected $table            = 'sponsor_rank';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['rank','label'];

    // Validation
    protected $validationRules      = [
        'rank'     => 'required|integer',
        'label'     => 'required|max_length[50]',
    ];
    protected $validationMessages   = [
        'rank'      => [
            'required'  => 'Le rang est obligatoire',
            'integer'   => 'Le rang doit être un nombre'
        ],
        'label'     => [
            'required'  => 'Le label est obligatoire',
            'max_length' => 'Le label ne doit pas excéder 50 caractères'
         ]
    ];
}
