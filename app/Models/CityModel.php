<?php

namespace App\Models;

use App\Traits\Select2Searchable;
use CodeIgniter\Model;

class CityModel extends Model
{
    use Select2Searchable;

    protected $table            = 'city';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['zip_code','label','department_name','department_number','region_name'];

    // Validation
    protected $validationRules = [
        'zip_code' => 'required|min_length[5]|max_length[5]',
        'label'     => 'required|max_length[255]',
        'department_name'     => 'required|max_length[255]',
        'department_number'     => 'required|min_length[2]|max_length[3]',
        'region_name'     => 'required|max_length[255]',
    ];
    protected $validationMessages   = [
        'zip_code' => [
            'required' => 'Le code postal est obligatoire',
            'min_length' => 'Le code postal doit faire 5 caractères',
            'max_length' => 'Le code postal doit faire 5 caractères'
        ],
        'label'     => [
            'required' => 'Le nom de la ville est obligatoire',
            'max_length' => 'Le nom de la ville ne peut pas excéder 255 caractères'
        ],
        'department_name'     => [
            'required' => 'Le nom du département est obligatoire',
            'max_length' => 'Le nom du département ne peut pas excéder 255 caractères'
        ],
        'department_number'     => [
            'required' => 'Le numéro de département est obligatoire',
            'min_length' => 'Le numéro du département doit faire entre 2 et 3 caractères'
        ],
        'region_name'     => [
            'region_name' => 'Le nom de la région est obligatoire',
            'max_length' => 'Le nom de la région ne peut pas excéder 255 caractères'
        ]
    ];


    protected $select2SearchFields = ['label','zip_code'];
    protected $select2DisplayField = 'label,zip_code';
    protected $select2AdditionalFields = ['department_number','department_name'];
}
