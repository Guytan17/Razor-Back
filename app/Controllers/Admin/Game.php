<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClubModel;
use App\Models\DivisionModel;
use App\Models\GameModel;
use App\Models\GymModel;
use App\Models\ServiceModel;
use App\Models\ServiceGameModel;
use CodeIgniter\HTTP\ResponseInterface;

class Game extends AdminController
{
    protected $clubModel;
    protected $gameModel;
    protected $divisionModel;
    protected $gymModel;

    protected $serviceModel;
    protected $serviceGameModel;

    public function __construct(){
        $this->clubModel = new ClubModel();
        $this->gameModel = new GameModel();
        $this->divisionModel = new DivisionModel();
        $this->gymModel = new GymModel();
        $this->serviceModel = new ServiceModel();
        $this->serviceGameModel = new ServiceGameModel();
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
            $game->services = $this->serviceGameModel->getServicesByGame($id);
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

            //GESTION DES SERVICES
            //Récupération des services existants pour ce match
            $existingServices = $this->serviceGameModel->where('id_game', $id)->findAll();

            //On crée un équivalent de la clé composite (sans_id_game car déjà filtré)
            $existingKeys = [];
            if($existingServices){
                foreach($existingServices as $existingService){
                    $existingKeys[] = $existingService['id_service'].'-'.$existingService['id_member'];
                }
            }


            //suppression des services supprimés
            $deletedKeys = [];
            if($deletedServices){
                foreach($deletedServices as $deletedService){
                    $deletedKeys[] = $deletedService['id_service'].'-'.$deletedService['id_member'];
                }
            }

            if(!empty($deletedServices)){
                foreach($deletedServices as $deletedService){
                    $key = $deletedService['id_service'].'-'.$deletedService['id_member'];
                    if(in_array($key, $existingKeys)){
                        if(!$this->serviceGameModel->where('id_service', $deletedService['id_service'])->where('id_game',$id)->where('id_member',$deletedService['id_member'])->delete()){
                            $this->error(implode('<br>',$this->serviceGameModel->errors()));
                        }
                    }
                }
            }

            //Enregistrement des services en BDD
            if(!empty($services)){
                foreach($services as $service){
                    $key=$service['id_service'].'-'.$service['id_member'];
                    if(!in_array($key, $existingKeys)){
                        $dataService = [
                            'id_service' => $service['id_service'],
                            'id_game' => $id,
                            'id_member' => $service['id_member'],
                            'details' => $service['details']
                        ];
                        if(!$this->serviceGameModel->insert($dataService)){
                            $this->error(implode('<br>',$this->serviceGameModel->errors()));
                        }
                    }
                }
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
