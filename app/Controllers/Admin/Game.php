<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClubModel;
use App\Models\DivisionModel;
use App\Models\GameModel;
use App\Models\GymModel;
use CodeIgniter\HTTP\ResponseInterface;

class Game extends AdminController
{
    protected $clubModel;
    protected $gameModel;
    protected $divisionModel;
    protected $gymModel;

    public function __construct(){
        $this->clubModel = new ClubModel();
        $this->gameModel = new GameModel();
        $this->divisionModel = new DivisionModel();
        $this->gymModel = new GymModel();
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
                'home_score' => $this->request->getPost('home_score') ? intval($this->request->getPost('home_score')): null,
                'away_score' => $this->request->getPost('away_score') ? intval($this->request->getPost('away_score')): null,
            ];

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
}
