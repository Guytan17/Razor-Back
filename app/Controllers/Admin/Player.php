<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlayerModel;
use CodeIgniter\HTTP\ResponseInterface;

class Player extends BaseController
{
    protected PlayerModel $playerModel;

    public function __construct(){
        $this->playerModel = new PlayerModel();
    }

    public function searchPlayer()
    {
        $request = $this->request;

        //Vérification Ajax
        if(!$request->isAJAX()) {
            return $this->response->setJSON(['error'=> 'Requête non autorisée']);
        }

        //Paramètres de recherche
        $search = $request->getget('search') ?? '';
        $page = (int) $request->getget('page') ?? 1;
        $limit = 25;

        // Récupération des conditions dynamiques
        $idTeam = $request->getGet('id_team');

        //Utilisation de la méthode du Model (via le trait)
        $result = $this->playerModel->searchWithTeamId($search,$page,$limit,$idTeam
        );

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
