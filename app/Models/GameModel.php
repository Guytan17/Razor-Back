<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;
use App\Entities\Game;

class GameModel extends Model
{
    use DataTableTrait;

    protected $table            = 'game';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Game::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['fbi_number','e_marque_code', 'id_gym','schedule','id_division','id_category','mvp','home_team','away_team','score_home','score_away'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'fbi_number' => 'permit_empty|max_length[10]',
        'e_marque_code' => 'permit_empty|max_length[10]',
        'id_gym' => 'permit_empty|integer',
        'schedule' => 'permit_empty|valid_date',
        'id_division' => 'permit_empty|integer',
        'id_category' => 'integer',
        'mvp' => 'permit_empty|integer',
        'home_team' => 'permit_empty|integer',
        'away_team' => 'permit_empty|integer',
        'score_home' => 'permit_empty|integer',
        'score_away' => 'permit_empty|integer'
    ];
    protected $validationMessages   = [
        'fbi_number' => [
            'max_length' => 'Le numéro de match ne peut pas excéder 10 caractères'
        ],
        'e_marque_code' => [
            'max_length' => 'Le code e-marque ne peut pas excéder 10 caractères'
        ],
        'id_gym' => [
            'integer' => 'L\'ID du gymnase doit être un nombre entier',
        ],
        'schedule' => [
            'valid_date' => 'L\'horaire du match doit être une date valide'
        ],
        'id_division' => [
            'integer' => 'L\'ID du championnat doit être un nombre entier'
        ],
        'id_category' => [
            'integer' => 'L\'ID de la category doit être un nombre entier',
        ],
        'mvp' => [
            'integer', 'Le MVP doit être l\'ID d\'un joueur'
        ],
        'home_team' => [
            'integer' => 'L\'ID de l\'équipe à domicile doit être un entier'
        ],
        'away_team' => [
            'integer' => 'L\'ID de l\'équipe à l\'extérieur doit être un entier'
        ],
        'score_home' => [
            'integer' => 'Le score de l\'équipe à domicile doit être un entier'
        ],
        'score_away' => [
            'integer' => 'Le score de l\'équipe à l\'extérieur doit être un entier'
        ]
    ];
    public function getDataTableConfig() {
        return [
            'searchable_fields' => [
                'id',
                'fbi_number',
                'category',
                'division',
                'opponent',
                'schedule',
                'place',
                'game.deleted_at'
            ],
            'joins' => [
                [
                    'table' => 'division',
                    'condition' => 'game.id_division = division.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'category',
                    'condition' => 'game.id_category = category.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'team as team_home',
                    'condition' => 'team_home.id = game.home_team',
                    'type' => 'inner'
                ],
                [
                    'table' => 'team as team_away',
                    'condition' => 'team_away.id = game.away_team',
                    'type' => 'inner'
                ],
                [
                    'table' => 'club as club_home',
                    'condition' => 'team_home.id_club = club_home.id',
                    'type' => 'inner'
                ],
                [
                    'table' => 'club as club_away',
                    'condition' => 'team_away.id_club = club_away.id',
                    'type' => 'inner'
                ],
                [
                    'table' => 'gym',
                    'condition' => 'game.id_gym = gym.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'address',
                    'condition' => 'gym.id_address = address.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'city',
                    'condition' => 'address.id_city = city.id',
                    'type' => 'left'
                ]
            ],
            'select' =>"
            game.id,
            game.fbi_number,
            category.name as category,
            division.name as division,
            CONCAT (
                CASE
                    WHEN club_home.id=1 THEN club_away.name
                    WHEN club_away.id=1 THEN club_home.name
                    ELSE ''
                END,
                ' - ',
                CASE
                    WHEN team_home.id_club=1 THEN team_away.name
                    WHEN team_away.id_club=1 THEN team_home.name
                    ELSE ''
                END)
            AS opponent,
            game.schedule,
            city.label as place,
            game.deleted_at
            "
        ];
    }

    public function reactiveGame($id) : bool{
        return $this->builder()
            ->where('id', $id)
            ->update(['deleted_at' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function getFullGame($id) {
        $this->select(
            'game.*,
            gym.name as gym_name,
            category.name as category,
            division.name as division,
            team_home.name as home_team_name,
            team_away.name as away_team_name,
            club_home.id as home_club,
            club_away.id as away_club,
            club_home.name as home_club_name,
            club_away.name as away_club_name,
            CONCAT(member.first_name," ",member.last_name) as mvp_name,');
        $this->join('category', 'game.id_category = category.id');
        $this->join('division', 'game.id_division = division.id');
        $this->join('gym', 'game.id_gym = gym.id');
        $this->join('team as team_home', 'game.home_team = team_home.id');
        $this->join('team as team_away', 'game.away_team = team_away.id');
        $this->join('club as club_home', 'team_home.id_club = club_home.id');
        $this->join('club as club_away', 'team_away.id_club = club_away.id');
        $this->join('member', 'game.mvp = member.id','left');
        $this->where('game.id', $id);
        return $this->first();
    }
}
