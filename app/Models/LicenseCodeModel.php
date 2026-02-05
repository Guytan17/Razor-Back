<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenseCodeModel extends Model
{
    protected $table            = 'license_code';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['code','explanation'];

    // Validation
    protected $validationRules      = [
        'code' => 'required|min_length[2]|max_length[2]',
        'explanation' => 'required|max_length[255]'
    ];
    protected $validationMessages   = [
        'code' => [
            'required' => 'le code licence est obligatoire',
            'min_length' => 'le code licence doit faire 2 caractères',
            'max_length' => 'le code licence doit faire 2 caractères'
        ],
        'explanation' => [
            'required' => 'Une courte définition du code est obligatoire',
            'max_length' => 'L\'explication du code ne peut pas excéder 255 caractères'
        ]
    ];
}
