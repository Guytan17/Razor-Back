<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table            = 'contact';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['entity_type','entity_id','mail','phone_number','details'];

    // Validation
    protected $validationRules      = [
        'entity_type'     => 'required|max_length[50]|in_list[member,club,gym,sponsor]',
        'entity_id'     => 'required|integer',
        'mail'     => 'permit_empty|valid_email|max_length[255]',
        'phone_number'     => 'permit_empty|max_length[10]',
        'details'     => 'permit_empty|max_length[255]',
    ];
    protected $validationMessages   = [
        'entity_type'     => [
            'required' => 'Le type d\'entité est obligatoire',
            'max_length' => 'Le type d\'entité ne peut pas excéder 50 caractères',
            'in_list '=> 'Le type d\'entité doit être parmi member,club,gym ou sponsor'
        ],
        'entity_id'     => [
            'required' => 'L\'ID de l\'entité est obligatoire',
            'integer' => 'L\'ID doit être un entier'
        ],
        'mail'     => [
            'valid_email' => 'L\'email doit être valide',
            'max_length' => 'L\'email ne peut pas dépasser 255 caractères'
        ],
        'phone_number'     => [
            'max_length' => 'Le numéro doit faire 10 caractères'
        ],
        'details' => [
            'max_length' => 'Les détails concernant le contact ne peuvent pas excéder 255 caractères'
        ]
    ];

    public function getContactsById($entity_id,$entity_type){
        $this->select('contact.*');
        $this->where('entity_id',$entity_id);
        $this->where('entity_type',$entity_type);
        return $this->findAll();
    }
}
