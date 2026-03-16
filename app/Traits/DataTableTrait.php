<?php

namespace App\Traits;

trait DataTableTrait
{
    /**
     * Configuration pour DataTable - à surcharger dans chaque modèle
     */
    protected function getDataTableConfig(): array
    {
        return [
            'searchable_fields' => ['id'], // Champs par défaut
            'joins' => [],
            'select' => '*',
            'with_deleted' => false, // Inclure les enregistrements soft deleted
            'where' => [],
            'groupBy' => null,
        ];
    }

    /**
     * Applique les jointures configurées
     */
    protected function applyJoins($builder, ?array $joins = null): void
    {
        $joins = $joins ?? $this->getDataTableConfig()['joins'];

        foreach ($joins as $join) {
            $builder->join(
                $join['table'],
                $join['condition'],
                $join['type'] ?? 'left'
            );
        }
    }

    /**
     * Applique les conditions WHERE
     * Supporte 3 formats :
     * - ['field', 'value'] : égalité simple
     * - ['field operator', 'value'] : avec opérateur (ex: 'created_at >', '2024-01-01')
     * - function($builder) {} : callback pour conditions complexes
     *
     * @param \CodeIgniter\Database\BaseBuilder $builder
     * @param array|null $where
     */
    protected function applyWhere($builder, ?array $where = null): void
    {
        $where = $where ?? $this->getConfigValue('where', []);

        foreach ($where as $condition) {
            if (is_array($condition)) {
                $count = count($condition);
                if ($count === 2) {
                    // Format simple : ['field', 'value']
                    $builder->where($condition[0], $condition[1]);
                } elseif ($count === 3) {
                    // Format avec opérateur : ['field', 'operator', 'value']
                    $builder->where($condition[0] . ' ' . $condition[1], $condition[2]);
                }
            } elseif (is_callable($condition)) {
                // Format callback pour conditions complexes
                $condition($builder);
            }
        }
    }

    /**
     * Applique les conditions de recherche
     */
    protected function applySearch($builder, string $searchValue, ?array $searchableFields = null): void
    {
        if (empty($searchValue)) {
            return;
        }

        $searchableFields = $searchableFields ?? $this->getDataTableConfig()['searchable_fields'];

        $builder->groupStart();
        foreach ($searchableFields as $index => $field) {
            if ($index === 0) {
                $builder->like($field, $searchValue);
            } else {
                $builder->orLike($field, $searchValue);
            }
        }
        $builder->groupEnd();
    }

    /**
     * Prépare le builder avec la configuration
     */
    protected function prepareBuilder(): \CodeIgniter\Database\BaseBuilder
    {
        $config = $this->getDataTableConfig();

        // Si with_deleted est activé, utiliser withDeleted()
        if (!empty($config['with_deleted']) && method_exists($this, 'withDeleted')) {
            $builder = $this->withDeleted()->builder();
        } else {
            $builder = $this->builder();
        }

        // Applique les jointures
        $this->applyJoins($builder, $config['joins']);

        // Applique la sélection
        if (!empty($config['select'])) {
            $builder->select($config['select'],false);
        }

        //Applique le where
        if (!empty($config['where'])) {
            $this->applyWhere($builder,$config['where']);
        }

        // Applique le GROUP BY
        if(!empty($config['groupBy'])) {
            $builder->groupBy($config['groupBy']);
        }

        return $builder;
    }

    public function getPaginated($start, $length, $searchValue, $orderColumnName, $orderDirection)
    {
        $config = $this->getDataTableConfig();
        $builder = $this->prepareBuilder();

        // Applique la recherche
        $this->applySearch($builder, $searchValue, $config['searchable_fields']);

        // Applique le tri
        if ($orderColumnName && $orderDirection) {
            $builder->orderBy($orderColumnName, $orderDirection);
        }

        if($length != -1) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResultArray();
    }

    public function getTotal()
    {
        $config = $this->getDataTableConfig();

        // Pour le total, on utilise le builder de base avec withDeleted si configuré
        if (!empty($config['with_deleted']) && method_exists($this, 'withDeleted')) {
            $builder = $this->withDeleted()->builder();
        } else {
            $builder = $this->builder();
        }

        $this->applyJoins($builder, $config['joins']);

        return $builder->countAllResults();
    }

    public function getFiltered($searchValue)
    {
        $config = $this->getDataTableConfig();
        $builder = $this->prepareBuilder();

        // Applique la recherche
        $this->applySearch($builder, $searchValue, $config['searchable_fields']);

        return $builder->countAllResults();
    }
}