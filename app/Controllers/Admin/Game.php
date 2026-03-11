<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClubModel;
use App\Models\DivisionModel;
use App\Models\GameModel;
use App\Models\GymModel;
use App\Models\ServiceModel;
use CodeIgniter\HTTP\ResponseInterface;

class Game extends AdminController
{
    protected $clubModel;
    protected $gameModel;
    protected $divisionModel;
    protected $gymModel;

    protected $serviceModel;

    public function __construct(){
        $this->clubModel = new ClubModel();
        $this->gameModel = new GameModel();
        $this->divisionModel = new DivisionModel();
        $this->gymModel = new GymModel();
        $this->serviceModel = new ServiceModel();
    }

    public function index()
    {
        $title = 'Matchs';
        $this->addBreadcrumb('Liste des matchs');
        $data = [
            'title' => $title,
        ];
        return $this->render('admin/game/index', $data);
    }

    public function form($id = null) {
        $this->addBreadcrumb('Liste des matchs', '/admin/game');
        $services = $this->serviceModel->findAll();

        if ($id != null) {
            $title = 'Modifier un match';
            $game = $this->gameModel->getFullGame($id);
        } else {
            $title = 'Ajouter un match';
        }
        $this->addBreadcrumb($title);
        $data = [
            'title' => $title,
            'game' => $game ?? null,
            'services' => $services ?? null,
            ];
        return $this->render('admin/game/form', $data);
    }

    public function saveGame($id = null) {
        try {
            //Récupération des données
            $dataGame = [
                'id' => $id,
                'fbi_number' => $this->request->getPost('fbi_number'),
                'e_marque_code' => $this->request->getPost('e_marque_code'),
                'id_gym' => $this->request->getPost('id_gym') ? intval($this->request->getPost('id_gym')): null,
                'schedule' => $this->request->getPost('schedule'),
                'id_category' => $this->request->getPost('id_category') ? intval($this->request->getPost('id_category')) : null,
                'id_division' => $this->request->getPost('id_division') ? intval($this->request->getPost('id_division')): null,
                'mvp' => $this->request->getPost('mvp') ? intval($this->request->getPost('mvp')): null,
                'home_team' => intval($this->request->getPost('home_team')),
                'away_team' => intval($this->request->getPost('away_team')),
                'score_home' => $this->request->getPost('home_score') ? intval($this->request->getPost('home_score')): null,
                'score_away' => $this->request->getPost('away_score') ? intval($this->request->getPost('away_score')): null,
            ];

            //Récupération des services
            $services = $this->request->getPost('services');
            $deletedServices = $this->request->getPost('deletedServices');

            //Variable pour savoir si c'est un nouveau match
            $newGame=empty($dataGame['id']);

            //Enregistrement en BDD
            if(!$this->gameModel->save($dataGame)){
                $this->error(implode('<br>',$this->gameModel->errors()));
                return $this->redirect('/admin/game');
            }
            if($newGame){
                $id=$this->gameModel->getInsertID();
            }



            //Messages de validation et récupération nouvel ID
            if($newGame){
                $this->success('Match créé avec succès');
            } else {
                $this->success('Match modifié avec succès');
            }

            return $this->redirect('/admin/game');

        } catch (\Exception $e){
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function switchActiveGame($idGame){

        $game = $this->gameModel->withDeleted()->find($idGame);

        //Test pour savoir si le match existe
        if(!$game) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Match introuvable'
            ]);
        }

        // Si le match est actif, on le désactive
        if(empty($game->deleted_at)) {
            $this->gameModel->delete($idGame);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Match désactivé',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->gameModel->reactiveGame($idGame)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Match activé',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                ]);
            }
        }
    }
}
