<?php

namespace App\Models;

use App\Traits\DataTableTrait;
use App\Traits\Select2Searchable;
use App\Traits\SlugTrait;
use CodeIgniter\Model;

class SponsorModel extends Model
{
    use DataTableTrait;
    use SlugTrait;
    use Select2Searchable;

    protected $table            = 'sponsor';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug','slogan','comments','created_at','updated_at','deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[255]',
        'slug' => 'max_length[255]',
        'slogan' => 'max_length[150]',
        'comments' => 'permit_empty',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Le nom du sponsor est obligatoire',
            'max_length' => 'Le nom du sponsor ne peut pas excéder 255 caractères',
        ],
        'slug' => [
            'max_length' => 'Le slug du sponsor ne peut pas excéder 255 caractères'
        ],
        [
          'slogan' => 'Le slogan de doit pas excéder 150 caractères'
        ],

    ];

    protected $beforeInsert   = ['generateUniqueSlugName'];
    protected $beforeUpdate   = ['generateUniqueSlugName'];

    public function getDataTableConfig(){
        return [
            'searchable_fields' => [
                'sponsor.id',
                'sponsor.name',
                'sponsor.id_rank',
                'sponsor_rank.label',
                'sponsor.specifications',
            ],
            'joins' => [
                [
                    'table' => 'sponsor_rank',
                    'condition' => 'sponsor.id_rank = sponsor_rank.id',
                    'type' => 'left'
                ],
                [
                    'table' => 'media',
                    'condition' => 'sponsor.id = media.entity_id AND media.entity_type = \'sponsor\'',
                    'type' => 'left'
                ],
            ],
            'select' => 'sponsor.id as id, name, id_rank, specifications, sponsor_rank.rank as rank, sponsor_rank.label as rank_label, media.file_path as logo_url,media.id as logo_id'
        ];
    }

    public function getFullSponsor($idSponsor): array{
        $this->select('sponsor.*, sponsor_rank.rank as rank_number, sponsor_rank.label as rank_label, dotation_type.type as dotation_type, media.id AS media_id');
        $this->join( 'sponsor_rank', 'sponsor_rank.id = sponsor.id_rank', 'left' );
        $this->join( 'dotation_type', 'dotation_type.id = sponsor.id_dotation_type', 'left' );
        $this->join('media', 'media.entity_id = '.$idSponsor.' and media.entity_type = \'sponsor\'','left');
        $this->where('sponsor.id', $idSponsor);
        return $this->first();
    }
}
