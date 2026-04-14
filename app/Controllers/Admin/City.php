<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CityModel;
use CodeIgniter\HTTP\ResponseInterface;

class City extends AdminController
{
    protected $cityModel;

    public function __construct(){
        $this->cityModel = new CityModel();
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
        $result = $this->cityModel->quickSearchForSelect2($search, $page, $limit, 'zip_code', 'ASC');

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
