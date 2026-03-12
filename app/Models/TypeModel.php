<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeModel extends Model
{
    protected $table            = 'type';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['code','explanation'];

    protected $validationRules = [
        'code' => 'required|max_length[2]',
        'explanation' => 'required|max_length[255]',
    ];
    protected $validationMessages = [
        'code' => [
            'required' => 'Le code est obligatoire',
            'max_length' => 'Le code doit faire 2 caractères',
        ],
        'explanation' => [
            'required' => 'L\'explication est obligatoire',
            'max_length' => 'L\'explication ne peut pas excéder 255 caractères'
        ]
    ];
}
