<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class TechnicalFoulModel extends Model
{
    use DataTableTrait;

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

    public function getDataTableConfig(){
        return [
            'searchable_fields' => [
                'technical_foul.*',
                'member.first_name',
                'member.last_name',
                'type.code as type',
                'classification.code as classification',
                'game.fbi_number'
            ],
            'joins' => [
                [
                    'table' => 'member',
                    'condition' => 'member.id = technical_foul.id_member',
                    'type' => 'inner'
                ],
                [
                    'table' => 'type',
                    'condition' => 'type.id = technical_foul.id_type',
                    'type' => 'inner'
                ],
                [
                    'table' => 'classification',
                    'condition' => 'classification.id = technical_foul.id_classification',
                    'type' => 'inner'
                ],
                [
                    'table' => 'game',
                    'condition' => 'game.id = technical_foul.id_game',
                    'type' => 'inner'
                ],
                [
                    'table' => 'team as home_team',
                    'condition' => 'home_team.id = game.home_team',
                    'type' => 'inner'
                ],
                [
                    'table' => 'team as away_team',
                    'condition' => 'away_team.id = game.away_team',
                    'type' => 'inner'
                ],
                [
                    'table' => 'club as home_club',
                    'condition' => 'home_club.id = home_team.id_club',
                    'type' => 'inner'
                ],
                [
                    'table' => 'club as away_club',
                    'condition' => 'away_club.id = away_team.id_club',
                    'type' => 'inner'
                ]
            ],
            'select' => '
                technical_foul.*,
                CONCAT(member.first_name, " ", member.last_name) as member_name,
                type.code as type,
                classification.code as classification,
                game.fbi_number as game_fbi_number,
                home_team.id as home_team_id,
                home_team.name as home_team_name,
                away_team.id as away_team_id,
                away_team.name as away_team_name,
                home_club.id as home_club_id,
                home_club.name as home_club_name,
                away_club.id as away_club_id,
                away_club.name as away_club_name
            '
        ];
    }

    public function getTechnicalFoulsWithInfos(){

        $this->select('
            technical_foul.*,
            game.fbi_number as game_fbi_number,
            game.schedule,
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
