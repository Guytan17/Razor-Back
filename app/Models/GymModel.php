<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\Select2Searchable;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class GymModel extends Model
{
    use SlugTrait;
    use DataTableTrait;
    use Select2Searchable;

    protected $table = 'gym';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['fbi_code', 'name', 'id_address', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation

    protected $validationMessages = [
        'fbi_code' => [
            'max_length' => 'Le code FBI ne peut pas excéder 255 caractères',
            'is_unique' => 'Le code FBI existe déjà',
        ],
        'name' => [
            'required' => 'Le nom du gymnase est obligatoire',
            'max_length' => 'Le nom du gymnase ne peut pas excéder 255 caractères',
        ],
        'id_address' => [
            'integer' => 'L\'ID de l\'adresse doit être un entier',
        ]
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['generateUniqueSlugName'];
    protected $beforeUpdate = ['generateUniqueSlugName'];
    public function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => [
                'id',
                'name',
                'fbi_code',
                'gym_city'
            ],
            'joins' => [
                [
                    'table' => 'address',
                    'condition' => 'gym.id_address = address.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'city',
                    'condition' => 'address.id_city = city.id',
                    'type' => 'inner'
                ]
            ],
            'select' =>
                'gym.id,
                gym.name,
                gym.fbi_code,
                city.label as gym_city'
        ];
    }

    public function getGymById(int $id) {
        $this->select('gym.*, address.address_1,address.address_2,address.id_city,address.gps_location,city.zip_code,city.label,city.department_name,city.department_number,city.region_name');
        $this->join('address', 'gym.id_address = address.id');
        $this->join('city', 'address.id_city = city.id');
        $this->where('gym.id', $id);
        return $this->first();
    }

    //fonction save personnalisée pour gérer la règle d'unicité du fbi_code
    public function saveGym(array $dataGym){
        $idGym = $dataGym['id'] ?? null;

        //définition règles de validation
        $rules = [
            'name' => 'required|max_length[255]',
            'id_address' => 'integer',
        ];

        $rules['fbi_code'] =
            $idGym != null
            ? 'max_length[255]'
            : 'max_length[255]|is_unique[gym.fbi_code]'
        ;

        //Mise en place des règles de validation
        $this->setValidationRules($rules);

        //Enregistrement des données
        return $this->save($dataGym);
    }

}

