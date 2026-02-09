<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Member extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'first_name' => 'string',
        'last_name'  => 'string',
        'slug'       => 'string',
        'date_of_birth' => 'date',
        'license_number' => 'string',
        'id_license_code' => 'integer',
        'license_status' => 'integer',
        'balance' => 'float',
        'id_role' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}
