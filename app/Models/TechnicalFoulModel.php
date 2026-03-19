<?php

namespace App\Models;

use CodeIgniter\Model;

class TechnicalFoulModel extends Model
{
    protected $table            = 'technical_foul';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
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

    public function getTechnicalFoulsWithInfos(){

        $this->select('
            technical_foul.*,
            game.fbi_number as game_fbi_number,
            type.code as type_code,
            classification.code as classification_code,
            CONCAT (member.first_name, " ", member.last_name) as member_name,
            game.home_team as home_team_id,
            home_team.name as home_team_name,
            game.away_team as away_team_id,
            away_team.name as away_team_name,
            home_team.id_club as home_team_club,
            home_club.name as home_club_name,
            away_team.id_club as away_team_club,
            away_club.name as away_club_name,
            
        ');
        $this->join('game', 'game.id = technical_foul.id_game');
        $this->join('type', 'type.id = technical_foul.id_type');
        $this->join('classification', 'classification.id = technical_foul.id_classification');
        $this->join('member', 'member.id = technical_foul.id_member');
        $this->join('team as home_team', 'home_team.id = game.home_team');
        $this->join('team as away_team', 'away_team.id = game.away_team');
        $this->join('club as home_club', 'home_club.id = home_team.id_club');
        $this->join('club as away_club', 'away_club.id = away_team.id_club');

        return $this->findAll();
    }
}
