<?php
namespace App\Traits;

trait SlugTrait
{
    protected function generateUniqueSlugName(array|object $data) {
        return $this->generateUniqueSlug($data, 'name');
    }

    protected function generateUniqueSlugTitle(array|object $data) {
        return $this->generateUniqueSlug($data, 'title');
    }

    /**
     * Génère un slug unique pour un enregistrement basé sur un champ donné.
     *
     * Cette fonction :
     * 1. Détecte si on travaille avec un tableau ou une entité
     * 2. Vérifie si le champ existe et si la valeur n'a pas changé en édition
     * 3. Crée un slug "propre" à partir de la valeur du champ
     * 4. Vérifie si le slug existe déjà dans la base
     * 5. Si nécessaire, ajoute un suffixe numérique pour garantir l'unicité
     *
     * @param array|object $data Tableau ou entité contenant les données
     * @param string $field Nom du champ à utiliser pour générer le slug ('name', 'title', etc.)
     * @return array|object Les données avec un champ 'slug' unique ajouté/modifié
     */
    protected function generateUniqueSlug(array|object $data, string $field)
    {
        $isEntity = is_object($data);

        // Récupération du champ source et de l'ID
        if ($isEntity) {
            $fieldValue = $this->getEntityValue($data, $field);
            $id = $this->getEntityValue($data, 'id');
        } else {
            $fieldValue = $data['data'][$field] ?? null;
            $id = $data['id'] ?? null;
        }

        // Si aucune valeur n'est fournie, on retourne les données telles quelles
        if (!$fieldValue) {
            return $data;
        }

        // Si on est en édition, vérifier si la valeur a changé
        if ($id) {
            $old = $this->where('id', $id)->first();

            // Si la valeur n'a pas changé, pas besoin de modifier le slug

            if ($old) {
                $oldValue = is_object($old)
                    ? $this->getEntityValue($old, $field)
                    : ($old[$field] ?? null);

                if ($oldValue == $fieldValue) {
                    return $data;
                }
            }
        }

        // Génère le slug de base
        $base_slug = generateSlug($fieldValue);
        $slug = $base_slug;

        // Compte combien d'enregistrements ont déjà cette valeur (hors l'édition en cours)
        $this->where('slug', $slug);
        if ($id) {
            $this->where('id !=', $id);
        }

        // Si la valeur existe déjà, on cherche un slug disponible
        if ($this->countAllResults() > 0) {
            $availableSlug = false;
            $count = 1;

            // Boucle jusqu'à trouver un slug unique
            while (!$availableSlug) {
                $availableSlug = $this->where('slug', $slug)->countAllResults() == 0;

                if (!$availableSlug) {
                    $count++;
                    $slug = $base_slug . '-' . $count;
                }
            }
        }

        // Attribue le slug unique selon le type de données
        if ($isEntity) {
            $this->setEntityValue($data, 'slug', $slug);
        } else {
            $data['data']['slug'] = $slug;

        }

        return $data;
    }

    /**
     * Récupère une valeur depuis une entité de manière flexible
     *
     * @param object $entity L'entité
     * @param string $field Nom du champ
     * @return mixed La valeur du champ
     */
    protected function getEntityValue(object $entity, string $field)
    {
        // Essaie d'abord avec un getter (getName, getTitle, getId, etc.)
        $getter = 'get' . ucfirst($field);
        if (method_exists($entity, $getter)) {
            return $entity->$getter();
        }

        // Sinon accès direct à la propriété
        if (property_exists($entity, $field)) {
            return $entity->$field;
        }

        return null;
    }

    /**
     * Définit une valeur dans une entité de manière flexible
     *
     * @param object $entity L'entité
     * @param string $field Nom du champ
     * @param mixed $value Valeur à définir
     * @return void
     */
    protected function setEntityValue(object $entity, string $field, $value)
    {
        // Essaie d'abord avec un setter (setSlug, etc.)
        $setter = 'set' . ucfirst($field);
        if (method_exists($entity, $setter)) {
            $entity->$setter($value);
            return;
        }

        // Sinon accès direct à la propriété
        if (property_exists($entity, $field)) {
            $entity->$field = $value;
        }
    }
}