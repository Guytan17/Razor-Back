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
                'team.name',
                'team.slug',
                'season.name',
                'category.name',
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
            'where' => [
                ['team.id_club','1']
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
    protected $select2DisplayField = 'id,name';
    protected $select2AdditionalFields = ['category_name','season_name','club_name'];

    public function reactiveTeam($id) : bool{
        return $this->builder()
            ->where('id', $id)
            ->update(['deleted_at' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function getTeamsByClub($id_club) {
        $this->select('team.*,category.name as category_name,season.name as season_name');
        $this->join('category', 'category.id = team.id_category');
        $this->join('season', 'season.id = team.id_season');
        $this->where('team.id_club', $id_club);
        return $this->findAll();
    }

    public function searchTeamWithInfos($search='',$page=1,$limit=20,$conditions=[]) {
        $this->select('team.*,category.name as category_name,season.name as season_name,club.name as club_name');
        $this->join('category', 'category.id = team.id_category');
        $this->join('season', 'season.id = team.id_season');
        $this->join('club', 'club.id = team.id_club','left');

        return $this->searchForSelect2(
            search:$search,
            page:$page,
            limit:$limit,
            searchFields: $this->select2SearchFields,
            displayField: $this->select2DisplayField,
            additionalFields: $this->select2AdditionalFields ?? [],
            conditions:$conditions,
            orderBy: 'team.id'
        );
    }

}
