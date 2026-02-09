<?php

namespace App\Models;

use App\Traits\SlugTrait;
use CodeIgniter\Model;
use App\Entities\Member;

class MemberModel extends Model
{
    use SlugTrait ;
    protected $table            = 'member';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Member::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['first_name', 'last_name', 'slug', 'date_of_birth', 'license_number', 'id_license_code', 'balance', 'id_role'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'first_name' => 'required|max_length[255]',
        'last_name'  => 'required|max_length[255]',
        'slug'       => 'max_length[255]',
        'date_of_birth' => 'required|valid_date',
        'license_number' => 'permit_empty|max_length[10]',
        'id_license_code' => 'permit_empty|integer',
        'balance' => 'permit_empty|integer',
        'id_role' => 'required|integer'

    ];
    protected $validationMessages   = [
        'first_name' => [
            'required' => 'Le prénom est obligatoire',
            'max_length' => 'Le prénom ne peut pas excéder 255 caractères'
        ],
        'last_name' => [
            'required' => 'Le nom est obligatoire',
            'max_length' => 'Le nom ne peut pas excéder 255 caractères'
        ],
        'slug' => [
            'max_length' => 'Le slug ne peut pas excéder 255 caractères',
        ],
        'date_of_birth' => [
            'required' => 'La date de naissance est obligatoire',
            'valid_date' => 'La date de naissance est incorrect'
        ],
        'license_number' => [
            'max_length' => 'Le numéro de licence ne doit pas excéder 10 caractères'
        ],
        'id_license_code' => [
            'integer' => 'L\'ID de licence doit être un nombre entier'
        ],
        'balance' => [
            'integer' => 'Le montant doit être un nombre entier'
        ],
        'id_role' => [
            'integer'=> 'L\'ID du rôle doit être un nombre entier'
        ]
    ];
    // Callbacks
    protected $beforeInsert   = ['generateUniqueSlugName'];
    protected $beforeUpdate   = ['generateUniqueSlugName'];
  }
