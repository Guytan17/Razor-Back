<?php

if (!function_exists('generate_slug')) {
    function generateSlug($string)
    {
        // Normaliser la chaîne pour enlever les accents
        $string = \Normalizer::normalize($string, \Normalizer::FORM_D);
        $string = preg_replace('/[\p{Mn}]/u', '', $string);

        // Convertir les caractères spéciaux en minuscules et les espaces en tirets
        $string = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));

        return $string;
    }
}



if (!function_exists('get_auth_groups')) {
    /**
     * Récupère la liste des groupes disponibles depuis la configuration Shield
     * Retourne un tableau formaté pour une utilisation facile dans les vues
     *
     * @return array Tableau de groupes avec leur nom, titre et description
     *               Format: [['name' => 'admin', 'title' => 'Admin', 'description' => '...'], ...]
     *
     * @example
     * $groups = get_auth_groups();
     * foreach ($groups as $group) {
     *     echo $group['name'];  // ex: 'admin'
     *     echo $group['title']; // ex: 'Admin'
     * }
     */
    function get_auth_groups(): array
    {
        // Récupérer la configuration Shield des groupes
        $authGroupsConfig = config('AuthGroups');
        $groups = [];

        // Transformer le tableau de configuration en format utilisable
        // La config contient : 'groupname' => ['title' => '...', 'description' => '...']
        foreach ($authGroupsConfig->groups as $groupName => $groupData) {
            $groups[] = [
                'name'        => $groupName,
                'title'       => $groupData['title'] ?? $groupName,
                'description' => $groupData['description'] ?? '',
            ];
        }

        return $groups;
    }
}


