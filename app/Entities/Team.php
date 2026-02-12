<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Team extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'name' => 'string',
        'slug' => 'string',
        'id_season' => 'int',
        'id_category' => 'int',
        'id_club' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}
