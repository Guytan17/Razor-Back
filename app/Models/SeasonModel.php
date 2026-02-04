<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class SeasonModel extends Model
{
    use DataTableTrait;

    protected $table            = 'season';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','start_date','end_date'];

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
        'start_date' => 'valid_date|permit_empty',
        'end_date' => 'valid_date|permit_empty',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom de la saison est obligatoire',
            'max_length' => 'Le nom de la saison ne peut pas excéder 255 caractères'
        ],
        'start_date' => [
            'valid_date' => 'La date de début saison doit être valide',
        ],
        'end_date' => [
            'valid_date' => 'La date de fin de saison doit être valide',
        ]
    ];

    public function getDataTableConfig() : array {
        return [
            'searchable_fields' => [
                'season.id',
                'season.name',
                'season.start_date',
                'season.end_date'
            ],
            'joins' => [],
            'select'=> 'season.id, season.name, season.start_date, season.end_date',
        ];
    }
}
