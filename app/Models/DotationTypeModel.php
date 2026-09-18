<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class DotationTypeModel extends Model
{
    use DataTableTrait;

    protected $table            = 'dotation_type';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['type'];

    // Validation
    protected $validationRules      = [
        'type'     => 'required|max_length[50]',
    ];
    protected $validationMessages   = [
        'type'     => [
            'required'  => 'Le type est obligatoire',
            'max_length' => 'Le type ne doit pas excéder 50 caractères'
        ]
    ];

    public function getDataTableConfig() {
        return [
            'searchable_fields' => [
                'id',
                'type'],
            'joins' => [],
            'select' => 'id,type',
        ];
    }
}
