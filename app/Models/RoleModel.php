<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class RoleModel extends Model
{
    use DataTableTrait;
    use SlugTrait;

    protected $table            = 'role';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','slug'];


    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
        'slug' => 'max_length[255]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom du rôle est obligatoire',
            'max_length' => 'Le nom du rôle ne peut pas excéder 255 caractères'
        ],
        'slug' => [
            'max_length' => 'Le slug ne peut pas excéder 255 caractères'
        ]
    ];

    protected $beforeInsert = ['generateUniqueSlugName'];

    protected $beforeUpdate = ['generateUniqueSlugName'];

    public function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'role.id',
                'role.name',
            ],
            'joins' => [],
            'select' => 'role.id, role.name',
        ];
    }
}
