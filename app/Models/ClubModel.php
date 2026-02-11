<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class ClubModel extends Model
{
    use DataTableTrait;
    use SlugTrait;

    protected $table            = 'club';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['code','name','slug','color_1','color_2','created_at','updated_at','deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'code' => 'required|max_length[10]',
        'name' => 'required|max_length[255]',
        'slug' => 'max_length[255]',
        'color_1' => 'max_length[50]',
        'color_2' => 'max_length[50]',

    ];
    protected $validationMessages   = [
        'code' => [
            'required' => 'Le code du club est obligatoire',
            'max_length' => 'Le du club ne peut pas excéder 10 caractères'
        ],
        'name' => [
            'required' => 'Le nom du club est obligatoire',
            'max_length' => 'Le nom du club ne peut pas excéder 255 caractères'
        ],
        'slug' => [
            'max_length' => 'Le slug ne doit pas excéder 255 caractères'
        ],
        'color_1' => [
            'max_length' => 'La couleur 1 ne peut pas excéder 50 caractères'
        ],
        'color_2' => [
            'max_length' => 'La couleur 2 ne peut pas excéder 50 caractères'
        ]

    ];

    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];
}
