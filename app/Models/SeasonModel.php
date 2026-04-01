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
        'start_date' => 'valid_date|date_before[end_date]',
        'end_date' => 'valid_date|date_after[start_date]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom de la saison est obligatoire',
            'max_length' => 'Le nom de la saison ne peut pas excéder 255 caractères'
        ],
        'start_date' => [
            'valid_date' => 'La date de début saison doit être valide',
            'date_before' => 'La date de début de saison doit être antérieure à celle de fin de saison'
        ],
        'end_date' => [
            'valid_date' => 'La date de fin de saison doit être valide',
            'date_after' => 'La date de fin de saison doit être ultérieure à celle de début de saison'
        ]
    ];

    public function getAllSeasons() {
        $this->select('season.*');
        return $this->findAll();
    }

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
