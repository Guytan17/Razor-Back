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
    protected $allowedFields    = ['id_member','id_team'];

    // Validation
    protected $validationRules      = [
        'id_member' => 'required|integer',
        'id_team' => 'required|integer',
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
    ];

    public function getPlayersByIdTeam($id) {
        $this->select('player.*,member.first_name as player_first_name,member.last_name as player_last_name, member.license_number as player_license_number');
        $this->where('player.id_team',$id);
        $this->join('member', 'player.id_member = member.id');
        return $this->findAll();
    }

    public function getPlayersByIdMember($id) {
        $this->select('player.*,team.name as team_name');
        $this->where('player.id_member',$id);
        $this->join('team', 'player.id_team = team.id');
        return $this->findAll();
    }
}
