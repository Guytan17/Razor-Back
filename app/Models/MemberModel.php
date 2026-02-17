<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\SlugTrait;
use CodeIgniter\Model;
use App\Entities\Member;

class MemberModel extends Model
{
    use SlugTrait ;
    use DataTableTrait;

    protected $table            = 'member';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Member::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['first_name', 'last_name', 'slug', 'date_of_birth', 'license_number', 'id_license_code','license_status','balance'];
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
        'license_status'=> 'integer|in_list[0,1]',
        'balance' => 'permit_empty|integer',

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
        'license_status' => [
            'integer' => 'Le statut de la licence doit être un nombre entier',
            'in_list' => 'Le statut de la licence doit être 0(inactif) ou 1(actif)'
        ],
        'balance' => [
            'integer' => 'Le montant doit être un nombre entier'
        ],
    ];
    // Callbacks
    protected $beforeInsert   = ['prepareName','generateUniqueSlugName','unsetVirtualName'];
    protected $beforeUpdate   = ['prepareName','generateUniqueSlugName','unsetVirtualName'];

    public function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'member.id',
                'last_name',
                'first_name',
                'license_number',
                'id_license_code',
                'member.deleted_at'
            ],
            'joins' => [
                [
                    'table' => 'role_member',
                    'condition' => 'member.id = role_member.id_member',
                    'type' => 'inner'
                ],
                [
                    'table' => 'role',
                    'condition' => 'role_member.id_role = role.id',
                    'type' => 'inner'
                ],
                [
                    'table' => 'license_code',
                    'condition' => 'member.id_license_code = license_code.id',
                    'type' => 'inner'
                ]
            ],
            'select' => '
            member.id,
            member.last_name,
            member.first_name,
            member.license_number,
            member.id_license_code,
            member.deleted_at,
            role.name as role_name,
            license_code.code as license_code,
            '
        ];
    }

    protected function prepareName(array $data) {
        if(isset($data['data']['last_name'],$data['data']['first_name'])) {
            $data['data']['name'] = trim($data['data']['last_name'].$data['data']['first_name']);
        }
        return $data;
    }
    public function unsetVirtualName(array $data) {
        unset($data['data']['name']);
        return $data;
    }

    public function reactiveMember($id) : bool{
        return $this->builder()
            ->where('id', $id)
            ->update(['deleted_at' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }

}
