<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleMemberModel extends Model
{
    protected $table            = 'role_member';
    protected $primaryKey       = 'id_member';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_member','id_role'];

    // Validation
    protected $validationRules      = [
        'id_member' => 'required|integer',
        'id_role' => 'required|integer'
    ];
    protected $validationMessages   = [
        'id_member' => [
            'required' => 'L\'ID du membre est obligatoire',
            'integer' => 'L\'ID du membre doit être un entier',
        ],
        'id_role' => [
            'required' => 'L\'ID du rôle est obligatoire',
            'integer' => 'L\'ID du rôle doit être un entier'
        ]
    ];

    public function getRoleMember($id_member)
    {
        $this->select('id_member,id_role');
        $this->where('id_member', $id_member);
        return $this->findAll();
    }
}
