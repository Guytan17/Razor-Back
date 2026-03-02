<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table            = 'address';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['address_1','address_2','id_city','gps_location','created_at','updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'address_1' => 'required|max_length[255]',
        'address_2' => 'permit_empty|max_length[255]',
        'id_city' => 'required|integer',
        'gps_location' => 'permit_empty|max_length[255]',
    ];
    protected $validationMessages   = [
        'address_1' => [
            'required' => 'L\'adresse est obligatoire',
            'max_length' => 'L\'adresse ne peut pas excéder 255 caractères',
        ],
        'address_2' => [
            'max_length' => 'Le complément d\'adresse ne peut pas excéder 255 caractères',
        ],
        'id_city' => [
            'required' => 'L\'ID de la ville est obligatoire',
            'integer' => 'L\'ID de la ville doit être un entier'
        ],
        'gps_location' => [
            'max_length' => 'Les coordonnées GPS ne peut pas excéder 255 caractères.'
        ]
    ];

}
