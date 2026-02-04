<?php
if (!function_exists('format_date_fr')){
    /**
     * @param $date - Date à renseigner
     * @param $format - format à renseigner, par défaut : dim. 1 janvier 1995
     * @return false|string
     * @throws Exception
     */

    function format_date_fr($date, $format = 'EEE d MMMM y'){
        return (new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::NONE,
            INTLDateFormatter::NONE,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
            $format
        ))->format(new DateTime($date));
    }
}
