<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class LeagueModel extends Model
{
    use DataTableTrait;
    use SlugTrait;

    protected $table = 'league';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'slug', 'id_season', 'id_category'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'slug' => 'max_length[255]',
        'id_season' => 'required|integer',
        'id_category' => 'required|integer',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom est obligatoire',
            'max_length' => 'Le nom ne peut pas excéder 255 caractères'
        ],
        'slug' => [
            'max_length' => 'Le slug ne peut pas excéder 255 caractères',
        ],
        'id_season' => [
            'required' => 'La saison est obligatoire',
            'integer' => 'L\'ID de la saison doit être un nombre entier',
        ],
        'id_category' => [
            'required' => 'La catégorie est obligatoire',
            'integer' => 'L\'ID de la catégorie doit être un nombre entier',
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['generateUniqueSlugName'];
    protected $beforeUpdate = ['generateUniqueSlugName'];

    public function getDataTableConfig(): array {
        return [
            'searchable_fields' =>
                [
                    'league.id',
                    'league.name',
                    'season',
                    'category',
                ],
            'joins' =>
            [
                [
                    'table' => 'season',
                    'condition' => 'league.id_season = season.id',
                    'type' => 'INNER'
                ],
                [
                    'table' => 'category',
                    'condition' => 'league.id_category = category.id',
                    'type' => 'INNER'
                ]
            ],
            'select' => 'league.id,league.name,season.name as season,category.name as category',
        ];
    }
}