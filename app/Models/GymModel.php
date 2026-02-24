<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class GymModel extends Model
{
    use SlugTrait;
    use DataTableTrait;

    protected $table            = 'gym';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['fbi_code','name','id_address','created_at','updated_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'fbi_code' => 'is_unique[gym.fbi_code]',
        'name' => 'required|max_length[255]',
        'id_address' => 'integer',
    ];
    protected $validationMessages   = [
        'fbi_code' => [
            'is_unique' => 'Le code FBI existe déjà',
        ],
        'name' => [
            'required' => 'Le nom du gymnase est obligatoire',
            'max_length' => 'Le nom du gymnase ne peut pas excéder 255 caractères',
        ],
        'id_address' => [
            'integer' => 'L\'ID de l\'adresse doit être un entier',
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert   = ['generateUniqueSlugName'];
    protected $beforeUpdate   = ['generateUniqueSlugName'];
}
