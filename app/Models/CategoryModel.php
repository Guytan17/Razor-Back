<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    use DataTableTrait;
    use SlugTrait;

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
        'gender' => 'required|in_list[mixed,man,woman]',
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
            'in_list'=>'Le genre doit être \'mixed\'(mixte),\'man\'(masculin) ou \'woman\'(féminin)'
        ]
    ];
    protected $beforeInsert   = ['generateUniqueSlugName'];
    protected $beforeUpdate   = ['generateUniqueSlugName'];

    public function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'category.id',
                'category.name',
                'category.gender'
            ],
            'joins' => [],
            'select' => '
                category.id, 
                category.name,
                category.gender'
            ,
        ];
    }
}
