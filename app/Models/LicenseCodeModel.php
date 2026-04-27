<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class LicenseCodeModel extends Model
{
    use DataTableTrait;

    protected $table            = 'license_code';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['code','explanation'];

    // Validation
    protected $validationRules      = [
        'id' => 'permit_empty|is_natural_no_zero', //sert à rendre le code unique pour insertion sans bloquer la modification
        'code' => 'required|max_length[2]|is_unique[license_code.code,id,{id}]',
        'explanation' => 'required|max_length[255]'
    ];
    protected $validationMessages   = [
        'id' => [
            'is_natural_no_zero' => 'Le champ id doit être un chiffre positif',
        ],
        'code' => [
            'required' => 'Le code licence est obligatoire',
            'max_length' => 'Le code licence doit faire 2 caractères maximum',
            'is_unique' => 'Ce code existe déjà'
        ],
        'explanation' => [
            'required' => 'Une courte définition du code est obligatoire',
            'max_length' => 'L\'explication du code ne peut pas excéder 255 caractères'
        ]
    ];

    public function getDataTableConfig():array {
        return [
            'searchable_fields' => [
                'license_code.code',
                'license_code.explanation'
            ],
            'joins' => [],
            'select' => 'license_code.id,license_code.code,license_code.explanation',
        ];
    }

    public function getAllLicenseCodes():array {
        $this->select('license_code.id,license_code.code,license_code.explanation');
        return $this->findAll();
    }
}
