<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\Select2Searchable;
use CodeIgniter\Model;
use App\Entities\Game;

class GameModel extends Model
{
    use DataTableTrait;
    use Select2Searchable;

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
        'score_home' => 'permit_empty|integer|less_than_equal_to[299]',
        'score_away' => 'permit_empty|integer|less_than_equal_to[299]'
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
            'integer' => 'Le score de l\'équipe à domicile doit être un entier',
            'less_than_equal_to' => 'Le score de l\'équipe à domicile doit être inférieur ou égal à 299'
        ],
        'score_away' => [
            'integer' => 'Le score de l\'équipe à l\'extérieur doit être un entier',
             'less_than_equal_to' => 'Le score de l\'équipe à l\'extérieur doit être inférieur ou égal à 299'
        ]
    ];
    public function getDataTableConfig() {
        return [
            'searchable_fields' => [
                'game.id',
                'game.fbi_number',
                'category.name',
                'division.name',
                'team_home.name',
                'team_away.name',
                'club_home.name',
                'club_away.name',
                'game.schedule',
                'city.label',
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
            CASE 
                WHEN team_home.id_club= 1 THEN team_home.name
                WHEN team_away.id_club= 1 THEN team_away.name
                ELSE ''
                END as team,
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

    protected $select2SearchFields = ['fbi_number,home_team_name,away_team_name'];
    protected $select2DisplayField = 'text';
    protected $select2AdditionalFields = ['schedule','category','home_team','home_club','away_team','away_club'];

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
        $this->join('division', 'game.id_division = division.id', 'left');
        $this->join('gym', 'game.id_gym = gym.id');
        $this->join('team as team_home', 'game.home_team = team_home.id');
        $this->join('team as team_away', 'game.away_team = team_away.id');
        $this->join('club as club_home', 'team_home.id_club = club_home.id');
        $this->join('club as club_away', 'team_away.id_club = club_away.id');
        $this->join('member', 'game.mvp = member.id','left');
        $this->where('game.id', $id);
        return $this->first();
    }

    public function searchGames($search='',$page=1,$limit=20) {
        //Requête pour avoir les infos nécessaires liées au match
        $this->select(
            'game.id,
            game.fbi_number,
            game.schedule,
            game.home_team,
            game.away_team,
            home_team.name as home_team_name,
            away_team.name as away_team_name,
            home_club.id as home_club,
            away_club.id as away_club,
            home_club.name as home_club_name,
            away_club.name as away_club_name,
            category.name as category,
            CONCAT(fbi_number," - ", home_team.name," ",home_club.name," / ",away_team.name," ",away_club.name) as text');
        $this->join('category', 'game.id_category = category.id','inner');
        $this->join('team as home_team', 'game.home_team = home_team.id','inner');
        $this->join('team as away_team', 'game.away_team = away_team.id','inner');
        $this->join('club as home_club', 'home_team.id_club = home_club.id', 'left');
        $this->join('club as away_club', 'away_team.id_club = away_club.id', 'left');


        return $this->searchForSelect2(
            search:$search,
            page:$page,
            limit:$limit,
            searchFields: $this->select2SearchFields,
            displayField: $this->select2DisplayField,
            additionalFields: $this->select2AdditionalFields,
            orderBy:'game.schedule',
            orderDirection: 'DESC'
        );
    }

    public function getGamesByMvpMember($idMember){
        $this->select('game.id as id_game,game.fbi_number,game.schedule,game.home_team,game.away_team,home_team.name as home_team_name,away_team.name as away_team_name,home_club.name as home_club_name,away_club.name as away_club_name');
        $this->join('team as home_team', 'game.home_team = home_team.id');
        $this->join('team as away_team', 'game.away_team = away_team.id');
        $this->join('club as home_club','home_team.id_club = home_club.id');
        $this->join('club as away_club', 'away_team.id_club = away_club.id');
        $this->where('game.mvp', $idMember);
        return $this->findAll();
    }

    public function getGamesByTeam($idTeam){
        $this->select('game.*,home_team.name as home_team_name,away_team.name as away_team_name,home_club.name as home_club_name,home_club.id as home_club_id,away_club.name as away_club_name,away_club.id as away_club_id');
        $this->join('team as home_team', 'game.home_team = home_team.id');
        $this->join('team as away_team', 'game.away_team = away_team.id');
        $this->join('club as home_club', 'home_team.id_club = home_club.id');
        $this->join('club as away_club', 'away_team.id_club = away_club.id');
        $this->where('game.home_team', $idTeam);
        $this->orWhere('game.away_team', $idTeam);

        return $this->findAll();
    }

    public function getGamesByGym($idGym){
        $this->select('game.*,home_team.name as home_team_name,away_team.name as away_team_name,home_club.name as home_club_name,away_club.name as away_club_name');
        $this->join('team as home_team', 'game.home_team = home_team.id');
        $this->join('team as away_team', 'game.away_team = away_team.id');
        $this->join('club as home_club', 'home_team.id_club = home_club.id');
        $this->join('club as away_club', 'away_team.id_club = away_club.id');
        $this->where('game.id_gym', $idGym);
        $this->orderBy('game.schedule','DESC');

        return $this->findAll();
    }
}
