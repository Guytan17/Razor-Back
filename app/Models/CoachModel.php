<?php

namespace App\Models;

use CodeIgniter\Model;

class CoachModel extends Model
{
    protected $table            = 'coach';
    protected $primaryKey       = 'id_member';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_member', 'id_team'];

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

    public function getCoachesByIdTeam($id) {
        $this->select('coach.*,member.first_name as coach_first_name,member.last_name as coach_last_name, member.license_number as coach_license_number');
        $this->where('coach.id_team',$id);
        $this->join('member', 'coach.id_member = member.id');
        return $this->findAll();
    }

    public function getCoachesByIdMember($id) {
        $this->select('coach.*,team.name as team_name');
        $this->where('coach.id_member',$id);
        $this->join('team', 'coach.id_team = team.id');
        return $this->findAll();
    }
}
