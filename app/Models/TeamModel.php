<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\Select2Searchable;
use App\Traits\SlugTrait;
use CodeIgniter\Model;
use App\Entities\Team;

class TeamModel extends Model
{
    use DataTableTrait;
    use SlugTrait;
    use Select2Searchable;

    protected $table            = 'team';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Team::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','slug','id_season','id_category','id_club','created_at','updated_at','deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
        'slug' => 'max_length[255]',
        'id_season' => 'required|integer',
        'id_category' => 'required|integer',
        'id_club' => 'required|integer',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom de l\'équipe est obligatoire.',
            'max_length' => 'Le nom de l\'équipe ne peut pas excéder 255 caractères'
        ],
        'slug' => [
            'max_length' => 'Le slug de l\'équipe ne peut pas excéder 255 caractères'
        ],
        'id_season' => [
            'required' => 'L\'ID de la saison est obligatoire.',
            'integer' => 'L\'ID de la saison doit être un entier.'
        ],
        'id_category' => [
            'required' => 'L\'ID de la catégorie est obligatoire.',
            'integer' => 'L\'ID de la catégorie doit être un entier.'
        ],
        'id_club' => [
            'required' => 'L\'ID du club est obligatoire.',
            'integer' => 'L\'ID du club doit être un entier.'
        ],
    ];

    protected $beforeInsert   = ['generateUniqueSlugName'];
    protected $beforeUpdate   = ['generateUniqueSlugName'];

    public function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'team.id',
                'name',
                'slug',
                'season_name',
                'category_name',
                'deleted_at',
            ],
            'joins' =>[
                [
                    'table' => 'season',
                    'condition'=> 'season.id = team.id_season',
                    'type' => 'INNER'
                ],
                [
                    'table' => 'category',
                    'condition'=> 'category.id = team.id_category',
                    'type' => 'INNER'
                ]
            ],

            'select' =>'
            team.id,
            team.name,
            category.name as category_name,
            season.name as season_name,
            deleted_at',
        ];
    }

    protected $select2SearchFields = ['name'];
    protected $select2DisplayField = 'name';

    public function reactiveTeam($id) : bool{
        return $this->builder()
            ->where('id', $id)
            ->update(['deleted_at' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }
}
