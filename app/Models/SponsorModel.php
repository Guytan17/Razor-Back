<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\Select2Searchable;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class SponsorModel extends Model
{
    use DataTableTrait;
    use SlugTrait;
    use Select2Searchable;

    protected $table            = 'sponsor';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'rank', 'specifications'];

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
        'slug' => 'max_length[255]',
        'rank' => 'integer',
        'specifications' => 'permit_empty',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom du sponsor est obligatoire',
            'max_length' => 'Le nom du sponsor ne peut pas excéder 255 caractères',
        ],
        'slug' => [
            'max_length' => 'Le slug du sponsor ne peut pas excéder 255 caractères'
        ],
        'rank' => [
            'integer' => 'Le rang doit être un chiffre'
        ],
    ];

    protected $beforeInsert   = ['generateUniqueSlugName'];
    protected $beforeUpdate   = ['generateUniqueSlugName'];

    public function getDataTableConfig(){
        return [
            'searchable_fields' => [
                'sponsor.id',
                'name',
                'rank',
                'specifications',
                'logo_url'
            ],
            'joins' => [
                [
                    'table' => 'media',
                    'condition' => 'sponsor.id = media.entity_id AND media.entity_type = \'sponsor\'',
                    'type' => 'left'
                ],
            ],
            'select' => 'sponsor.id as sponsor_id, name, rank, specifications,media.file_path as logo_url'
        ];
    }
}
