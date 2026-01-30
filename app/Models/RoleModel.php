<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'role';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name'];


    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom du rôle est obligatoire',
            'max_length' => 'Le nom du rôle ne peut pas excéder 255 caractères'
        ]
    ];

}
