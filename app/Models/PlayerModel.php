<?php

namespace App\Models;

use App\Traits\Select2Searchable;
use CodeIgniter\Model;

class PlayerModel extends Model
{
    use Select2Searchable;

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

    protected $select2SearchFields = ['first_name','last_name'];
    protected $select2DisplayField = 'first_name,last_name';

    //On surcharge le model avec la fonction permettant de rajouter le nom de la saison dans le select
    public function searchWithTeamId($teamId,$search='',$page=1,$limit=20){
        $builder = $this->builder();

        //jointure pour appliquer la condition d'équipe
        $builder->select('member.id as id_member,member.first_name,member.last_name');
        $builder->join('member', 'player.id_member = member.id');
        $builder->where('player.id_team',$teamId);

        // log de la requête SQL générée
        log_message('debug', 'SQL: ' . $builder->getCompiledSelect(false));

        return $this->searchForSelect2(
            search:$search,
            page:$page,
            limit:$limit,
            searchFields: $this->select2SearchFields,
            displayField: $this->select2DisplayField,
            orderBy: 'id_member',
            builder: $builder
        );
    }
}
