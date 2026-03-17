<?php

namespace App\Models;

use CodeIgniter\Model;

class TechnicalFoulModel extends Model
{
    protected $table            = 'technical_foul';
    protected $primaryKey       = 'id_game';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_game','id_member','id_type','id_classification','amount'];

    // Validation
    protected $validationRules      = [
        'id_game' => 'required|integer',
        'id_member' => 'required|integer',
        'id_type' => 'required|integer',
        'id_classification' => 'required|integer',
        'amount' => 'permit_empty|integer'
    ];
    protected $validationMessages   = [
        'id_game' => [
            'required' => 'L\'ID du match est obligatoire',
            'integer' => 'L\'ID du match doit être un entier'
        ],
        'id_member' => [
            'required' => 'L\'ID du joueur est obligatoire',
            'integer' => 'L\'ID du joueur doit être un entier'
        ],
        'id_type' => [
            'required' => 'L\'ID du type est obligatoire',
            'integer' => 'L\'ID du type doit être un entier'
        ],
        'id_classification' => [
            'required' => 'L\'ID de la classification est obligatoire',
            'integer' => 'L\'ID de la classification doit être un entier'
        ],
        'amount' => [
            'integer' => 'Le montant doit être un entier',
        ]
    ];
}
