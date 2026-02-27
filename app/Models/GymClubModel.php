<?php

namespace App\Models;

use CodeIgniter\Model;

class GymClubModel extends Model
{
    protected $table            = 'gym_club';
    protected $primaryKey       = 'id_club';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_club','id_gym','main_gym'];

    // Validation
    protected $validationRules      = [
        'id_club'      => 'required|integer',
        'id_gym'       => 'required|integer',
        'main_gym'     => 'required|in_list[0,1]',
    ];
    protected $validationMessages   = [
        'id_club' => [
            'required'  => 'L\'ID du club est obligatoire',
            'integer' => 'L\'ID du club doit être un nombre entier',
        ],
        'id_gym' => [
            'required'  => 'L\'ID du gymnase est obligatoire',
            'integer' => 'L\'ID du gymnase doit être un nombre entier',
        ],
        'main_gym' => [
            'required'  => 'Le champ\'gymnase principal\' est obligatoire',
            'in_list'   => 'Le champ\'gymnase principal\' doit être 0 (non principal) ou 1 (principal)',
        ]
    ];

    public function getClubsByIdGym($id_gym) {
        $this->select('gym_club.id_club, club.name, club.code, gym_club.main_gym');
        $this->join('club','club.id=gym_club.id_club');
        $this->where('gym_club.id_gym', $id_gym);
        return $this->findAll();
    }
}
