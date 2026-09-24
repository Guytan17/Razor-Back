<?php

namespace App\Models;

use CodeIgniter\Model;

class SeasonSponsorModel extends Model
{
    protected $table            = 'season_sponsor';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [ 'id_season','id_sponsor','id_rank','id_dotation_type','dotation_amount','specifications'];



    // Validation
    protected $validationRules      = [
        'id_season' => 'integer',
        'id_sponsor' => 'integer',
        'id_rank' => 'integer',
        'id_dotation_type' => 'required|integer',
        'dotation_amount' => 'required|integer',
        'specifications' => 'permit_empty',
    ];
    protected $validationMessages   = [
        'id_season' => [
            'integer' => 'L\'ID de la saison doit être un chiffre'
        ],
         'id_sponsor' => [
            'integer' => 'L\'ID du sponsor doit être un chiffre'
        ],
        'id_rank' => [
            'integer' => 'L\'ID du rang doit être un chiffre'
        ],
        'id_dotation_type' => [
            'required' => 'L\'ID du type de dotation est obligatoire',
            'integer' => 'L\'ID du type de dotation doit être un entier'
        ],
        'dotation_amount' => [
            'required' => 'Le montant de la dotation est obligatoire',
            'integer' => 'Le montant de la dotation doit être un entier'
        ]
    ];

}
