<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Game extends Entity
{
    protected $attributes = [
        'fbi_number' => null,
        'e_marque_code' =>null,
        'id_gym' =>null ,
        'schedule' =>null ,
        'id_division' =>null ,
        'mvp' =>null,
        'home_team' =>null,
        'away_team' => null,
        'score_home' =>null,
        'score_away' => null,
        'created_at' =>null ,
        'updated_at' => null,
        'deleted_at' =>null ,
    ];
  
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'fbi_number' => 'string',
        'e_marque_code' => 'string',
        'id_gym' => 'integer',
        'schedule' => 'datetime',
        'id_division' => 'integer',
        'mvp' => 'integer',
        'home_team' => 'integer',
        'away_team' => 'integer',
        'score_home' => 'integer',
        'score_away' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        ];
}
