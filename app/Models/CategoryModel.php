<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','gender'];

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
        'slug' => 'max_length[255]',
        'gender' => 'required|in_list[0,1,2]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom de la catégorie est obligatoire',
            'max_length' => 'Le nom de la catégorie ne peut pas excéder 255 caractères'
        ],
        'slug' => [
            'max_length' => 'Le nom de la catégorie ne peut pas excéder 255 caractères'
        ],
        'gender' => [
            'required' => 'Le genre est obligatoire',
            'in_list'=>'Le genre doit être 0(mixte),1(masculin) ou 2(féminin)'
        ]
    ];
    protected $beforeInsert   = ['generateUniqueSlugName'];
    protected $beforeUpdate   = ['generateUniqueSlugName'];
}
