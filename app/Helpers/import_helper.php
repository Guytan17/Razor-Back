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

if(!function_exists('csvDateToSql')){

    function csvDateToSql($date,$format='d/m/Y'){
        if(empty($date)){
            return "01/01/1900";
        }
        $validDate = \DateTime::createFromFormat($format, $date);

        if(!$validDate){
            return false;
        }
        return $validDate->format('Y-m-d');
    }
}
