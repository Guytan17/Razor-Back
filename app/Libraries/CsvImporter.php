<?php
namespace App\Libraries;

use PhpParser\Node\Expr\Throw_;

class CsvImporter
{
    protected function detectDelimiter($file){
        //on définit les delimiters à tester (ici , ; | et tabulation)
        $delimiters = [',',';','|',"\t"];

        //on ouvre le fichier et récupère la première ligne
        $handle = fopen($file, "r");
        $line = fgets($handle);

        fclose($handle);

        //on crée le tableau qui accueillera le résultat de chaque delimiter
        $results = [];

        //on boucle sur tous les delimiters et stocke le résultat
        foreach ($delimiters as $delimiter) {
            $fields = str_getcsv($line, $delimiter);
            $results[$delimiter] = count($fields);
        }

        //on retourne la clé (donc delimiter) du tableau résultats ayant eu le plus de champs, donc logiquement celui qui est le delimiter
        return array_search(max($results), $results);
    }

    public function parseCSV ($CSVFile){
        //définition du tableau qui accueillera les éléments du CSV (Gyms,Clubs,Games,...)
        $items = [];

        $delimiter = $this->detectDelimiter($CSVFile);

        //ouverture et lecture du fichier CSV
        $handle = fopen($CSVFile, 'r');

        if ($handle !== false){
            //extraction de la première ligne avec les libellés de colonnes pour créer les clés
            $dataKeys= fgetcsv($handle, 1000, $delimiter,'"');

            //suppression des éventuels caractères invisibles en début de fichier
            $dataKeys = preg_replace('/^\xEF\xBB\xBF/', '', $dataKeys);

            //définition du nombre de clés
            $cptKeys = count($dataKeys);

            //lecture du CSV tant qu'il y a des lignes
            while (($dataValue = fgetcsv($handle, 1000, $delimiter,'"')) !== false){
                //Si le nombre de clés et égal au nombre de valeurs, on commence à remplir le tableau avec les éléments du CSV
                if($cptKeys === count($dataValue)){
                    $items[] = array_combine($dataKeys,$dataValue);
                }  else {
                //Sinon,
                    //on retire les espaces autour de la ligne
                    $line = trim($dataValue[0]);
                    //si la ligne est entourée de guillemets, on les retire
                    if($line[0] === '"' && substr($line, -1) === '"') {
                        $line = substr($line, 1, -1);
                    }
                    //on retire les doubles guillemets s'il y en a
                    $line = str_replace('""', '"', $line);

                    //on retente d'extraire les infos de la ligne du CSV dans un tableau, et l'ajoute dans notre tableau d'items
                    $dataValue = str_getcsv($line,$delimiter,'"');
                    $items[] = array_combine($dataKeys, $dataValue);
                }
            }
            //On ferme le fichier
            fclose($handle);

            return $items;
        } else {
            throw new \Exception('Impossible d\'ouvrir le CSV');
        }
    }

}
