<?php

namespace App\Models;

use CodeIgniter\Model;

class DivisionTeamModel extends Model
{
    protected $table            = 'division_team';
    protected $primaryKey       = 'id_division';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_division','id_team'];

    // Validation
    protected $validationRules      = [
        'id_division' => 'integer|required',
        'id_team' => 'integer|required',
    ];
    protected $validationMessages   = [
        'id_division' => [
            'required' => 'L\'ID du championnat est obligatoire',
            'integer'=>'L\'ID du championnat doit être un entier'
        ],
        'id_team' => [
            'required' => 'L\'ID de l\'équipe est obligatoire',
            'integer'=> 'L\'ID de l\'équipe doit être un entier'
        ]
    ];

}
