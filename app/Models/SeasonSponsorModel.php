<?php

namespace App\Models;

use CodeIgniter\Model;

class SeasonSponsorModel extends Model
{
    protected $table            = 'season_sponsor';
    protected $primaryKey       = 'id_season';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [ 'id_season','id_sponsor','id_rank','id_dotation_type','dotation_amount','specifications'];



    // Validation
    protected $validationRules      = [
        'id_season' => 'integer',
        'id_sponsor' => 'integer',
        'id_rank' => 'integer',
        'id_dotation_type' => 'required|integer',
        'dotation_amount' => 'required|integer',
        'specifications' => 'permit_empty',
    ];
    protected $validationMessages   = [
        'id_season' => [
            'integer' => 'L\'ID de la saison doit être un chiffre'
        ],
         'id_sponsor' => [
            'integer' => 'L\'ID du sponsor doit être un chiffre'
        ],
        'id_rank' => [
            'integer' => 'L\'ID du rang doit être un chiffre'
        ],
        'id_dotation_type' => [
            'required' => 'L\'ID du type de dotation est obligatoire',
            'integer' => 'L\'ID du type de dotation doit être un entier'
        ],
        'dotation_amount' => [
            'required' => 'Le montant de la dotation est obligatoire',
            'integer' => 'Le montant de la dotation doit être un entier'
        ]
    ];

    public function getSeasonsBySponsor($id_sponsor){
        $this->select(' season_sponsor.*, season.name as season_name, sponsor_rank.rank as rank_number, sponsor_rank.label as rank_label, dotation_type.type as dotation_type,sponsor_rank.rank as rank_number, sponsor_rank.label as rank_label, dotation_type.type as dotation_type');
        $this->join('season', 'season.id = season_sponsor.id_season');
        $this->join( 'sponsor_rank', 'sponsor_rank.id = season_sponsor.id_rank', 'left' );
        $this->join( 'dotation_type', 'dotation_type.id = season_sponsor.id_dotation_type', 'left' );
        $this->where('season_sponsor.id_sponsor', $id_sponsor);
        return $this->findAll();

    }
}
