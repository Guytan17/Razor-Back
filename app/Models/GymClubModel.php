<?php

namespace App\Models;

use CodeIgniter\Model;

class GymClubModel extends Model
{
    protected $table            = 'gym_club';
    protected $primaryKey       = 'id_club';
    protected $useAutoIncrement = false;
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

    public function getGymsByIdClub($id_club) {
        $this->select('gym_club.*,gym.id as id,gym.name as gym_name,gym.fbi_code as gym_fbi_code,address.address_1 as gym_address, city.label as city');
        $this->join('gym','gym.id=gym_club.id_gym','inner');
        $this->join('address','address.id=gym.id_address','left');
        $this->join('city','city.id=address.id_city','left');
        $this->where('gym_club.id_club', $id_club);

        return $this->findAll();
    }
}
