<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceGameModel extends Model
{
    protected $table            = 'service_game';
    protected $primaryKey       = 'id_game';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_service','id_game','id_member','details'];

    // Validation
    protected $validationRules      = [
        'id_service' => 'integer|required',
        'id_game' => 'integer|required',
        'id_member' => 'integer|required',
        'details' => 'permit_empty|max_length[255]',
    ];
    protected $validationMessages   = [
        'id_service' => [
            'integer' => 'L\'ID du service doit être un entier',
            'required' => 'L\'ID du service est obligatoire',
        ],
        'id_game' => [
            'integer' => 'L\'ID du match doit être un entier',
            'required' => 'L\'ID du match est obligatoire',
        ],
        'id_member' => [
            'integer' => 'L\'ID du membre doit être un entier',
            'required' => 'L\'ID du membre est obligatoire',
        ],
        'details' => [
            'max_length' => 'Les précisions ne peuvent pas excéder 255 caractères'
        ]
    ];

    public function getServicesByGame($idGame){
        $this->select('service_game.*,service.label as service_label,member.last_name as member_last_name,member.first_name as member_first_name');
        $this->join('service','service.id = service_game.id_service');
        $this->join('member','member.id = service_game.id_member');
        $this->where('id_game',$idGame);
        return $this->findAll();
    }

}
