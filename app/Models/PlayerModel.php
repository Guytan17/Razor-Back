<?php

namespace App\Models;

use CodeIgniter\Model;

class PlayerModel extends Model
{
    protected $table            = 'player';
    protected $primaryKey       = 'id_member';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_member','id_team','overqualified','available','details'];

    // Validation
    protected $validationRules      = [
        'id_member' => 'required|integer',
        'id_team' => 'required|integer',
        'overqualified' => 'required|in_list[0,1,2]',
        'available' => 'required|in_list[0,1]',
        'details' => 'permit_empty',
    ];
    protected $validationMessages   = [
        'id_member' => [
            'required' => 'L\'ID du membre est obligatoire',
            'integer' => 'L\'ID du membre doit être un entier'
        ],
        'id_team' => [
            'required' => 'L\'ID de l\'équipe est obligatoire',
            'integer' => 'L\'ID de l\'équipe doit être un entier',
        ],
        'overqualified' => [
            'required' => 'Le niveau du surclassement est obligatoire',
            'in_list' => 'Le niveau du surclassement doit être 0(pas de surclassement), 1(suclassement simple) ou 2 (surclassement double)',
        ],
        'available' => [
            'required' => 'La disponibilité est obligatoire',
            'in_list' => 'La disponibilité doit être 0(indisponible) ou 1 (disponible)',
        ]
    ];
}
