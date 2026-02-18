<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Member extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

    protected $attributes = [
        'firstName' => null,
        'lastName' => null,
        'slug'=> null,
        'date_of_birth' => null,
        'license_number' => null,
        'id_license_code' => 5,
        'license_status' => 0,
        'balance'=>0,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    protected $casts   = [
        'first_name' => 'string',
        'last_name'  => 'string',
        'slug'       => 'string',
        'date_of_birth' => 'datetime',
        'license_number' => 'string',
        'id_license_code' => 'integer',
        'license_status' => 'integer',
        'balance' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}
