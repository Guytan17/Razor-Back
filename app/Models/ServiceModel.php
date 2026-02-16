<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use CodeIgniter\Model;

class ServiceModel extends Model
{
    use DataTableTrait;

    protected $table            = 'service';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['label'];
    // Validation
    protected $validationRules      = [
        'label' => 'required|max_length[255]'
    ];
    protected $validationMessages   = [
        'label' => [
            'required' => 'Le libellé du service est obligatoire',
            'max_length' => ' Le libellé du service ne peut pas excéder 255 caractères'
        ]
    ];

    public function getDataTableConfig(): array {
        return [
            'searchable_fields' => [
                'label',
            ],
            'joins' => [],
            'select' => 'id,label'
        ];
    }
}
