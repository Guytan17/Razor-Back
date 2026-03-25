<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlayerModel;
use CodeIgniter\HTTP\ResponseInterface;

class Player extends AdminController
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

        // Récupération des conditions dynamiques
        $idTeam = $request->getGet('id_team');

        //Paramètres de recherche
        $search = $request->getget('search') ?? '';
        $page = (int) $request->getget('page') ?? 1;
        $limit = 25;

        //Utilisation de la méthode du Model (via le trait)
        $result = $this->playerModel->searchWithTeamId($idTeam,$search,$page,$limit);

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
