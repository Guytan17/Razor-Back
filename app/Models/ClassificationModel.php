<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassificationModel extends Model
{
    protected $table            = 'classification';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['code','classification'];

    // Validation
    protected $validationRules      = [
        'code' => 'required|max_length[20]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
