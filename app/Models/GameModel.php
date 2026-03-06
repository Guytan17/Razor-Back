<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Game;

class GameModel extends Model
{
    protected $table            = 'game';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Game::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['fbi_number','e_marque_code', 'id_gym','schedule','id_division','mvp','home_team','away_team','score_home','score_away'];

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
}
