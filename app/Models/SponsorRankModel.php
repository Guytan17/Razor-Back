<?php

namespace App\Models;

use App\Traits\Select2Searchable;
use CodeIgniter\Model;
use App\Traits\DataTableTrait;

class SponsorRankModel extends Model
{
    use DataTableTrait;
    use Select2Searchable;

    protected $table            = 'sponsor_rank';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['rank','label'];

    // Validation
    protected $validationRules      = [
        'rank'     => 'required|integer',
        'label'     => 'required|max_length[50]',
    ];
    protected $validationMessages   = [
        'rank'      => [
            'required'  => 'Le rang est obligatoire',
            'integer'   => 'Le rang doit être un nombre'
        ],
        'label'     => [
            'required'  => 'Le label est obligatoire',
            'max_length' => 'Le label ne doit pas excéder 50 caractères'
         ]
    ];

    public function getDataTableConfig() {
        return [
            'searchable_fields' => [
                'id',
                'rank',
                'label'],
            'joins' => [],
            'select' => 'id,rank,label',
        ];
    }

    protected $select2SearchFields = ['rank','label'];
    protected $select2DisplayField = 'rank';
    protected $select2AdditionalFields = ['label'];
}
