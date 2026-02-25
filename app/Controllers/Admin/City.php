<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CityModel;
use CodeIgniter\HTTP\ResponseInterface;

class City extends AdminController
{
    protected $cm;

    public function __construct(){
        $this->cm = new CityModel();
    }
    public function searchCity(){
        $request = $this->request;

        //Vérification Ajax
        if(!$request->isAJAX()) {
            return $this->response->setJSON(['error'=> 'Requête non autorisée']);
        }

        //Paramètres de recherche
        $search = $request->getget('search') ?? '';
        $page = (int) $request->getget('page') ?? 1;
        $limit = 25;

        //Utilisation de la méthode du Model (via le trait)
        $result = $this->cm->quickSearchForSelect2($search, $page, $limit, 'label', 'ASC');

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
