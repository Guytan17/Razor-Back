<?php
if(!function_exists('normalizeCity')){


    /**
     * @param $city - nom de la ville à normaliser
     * @return array|string|string[]|null
     */
    function normalizeCity($city){
        //met le nom en minuscule
        $city = strtolower($city);
        //convertit les lettres avec accent en lettre sans accent
        $city = iconv('UTF-8', 'ASCII//TRANSLIT', $city);
        //retire tous les caractères autres que les lettres et les chiffres
        $city = preg_replace("/[^a-z0-9]/", '', $city);

        return $city;
    }
}
