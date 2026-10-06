<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Sponsor extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

    protected $attributes = [
        'name' => null,
        'slug' => null,
        'slogan' => null,
        'comments' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];

    protected $casts   = [
        'name' => 'string',
        'slug' => 'string',
        'slogan' => 'string',
        'comments' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public array $contacts = [];

    public array $seasons = [];

    /**
     * Récupère le logo du sponsor (entité Media)
     *
     * @return object|bool[]|float[]|int[]|null[]|object[]|string[] L'instance Media du logo dans le bon format ou null
     */
    public function getLogo(): array|object
    {
        $mediaModel = model('MediaModel');

        $logo = $mediaModel
            ->where('entity_type', 'sponsor_logo')
            ->where('entity_id', $this->id)
            ->first();

        return $logo;
    }

    public function isSponsorActive(): bool{
        return $this->attributes['deleted_at'] === null;
    }
}
