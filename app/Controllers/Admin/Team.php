<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\CoachModel;
use App\Models\PlayerModel;
use App\Models\TeamModel;
use App\Models\SeasonModel;
use App\Models\ClubModel;
use CodeIgniter\HTTP\ResponseInterface;

class Team extends AdminController
{
    protected $tm;
    protected $sm;
    protected $cm;
    protected $catm;
    protected $coachm;
    protected $playerm;

    public function __construct(){
        $this->tm = new TeamModel();
        $this->cm = new ClubModel();
        $this->catm = new CategoryModel();
        $this->sm = new SeasonModel();
        $this->coachm = new CoachModel();
        $this->playerm = new PlayerModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Liste des équipes',
        ];
        return $this->render('admin/team/index',$data);
    }

    public function form($id=null) {
        $this->addBreadcrumb('Liste des équipes','admin/team');
        $seasons = $this->sm->findAll();
        $clubs = $this->cm->findAll();
        $categories = $this->catm->findAll();

        if($id != null) {
            $title = 'Modifier une équipe';
            $this->addBreadcrumb($title);
            $team = $this->tm->find($id);
            $team->coachs = $this->coachm->getCoachesByIdTeam($id);
            $team->players = $this->playerm->getPlayersByIdTeam($id);

        } else {
            $title = 'Ajouter une équipe';
            $this->addBreadcrumb($title);
        }

        $data = [
            'title' => $title,
            'seasons' => $seasons,
            'clubs' => $clubs,
            'categories' => $categories,
            'team' => $team ?? null,
        ];

        return $this->render('admin/team/form',$data);
    }

    public function saveTeam ($id=null) {
//        $data=$this->request->getPost();
//        dd($data);
        try {
            //Récupération des données
            $dataTeam = [
                'id' => $id,
                'name' => $this->request->getPost('name'),
                'id_club' => $this->request->getPost('id_club'),
                'id_season' => $this->request->getPost('id_season'),
                'id_category' => $this->request->getPost('id_category'),
            ];

            $coachs = $this->request->getPost('coachs') ?? [];
            $players= $this->request->getPost('players') ?? [];

            //Préparation de la variable pour savoir si c'est une création
            $newTeam = empty($dataTeam['id']);

            //Création de l'objet Team
            $team = $newTeam ? new \App\Entities\Team() : $this->tm->withDeleted()->find($id);

            //Si je n'ai pas d'équipe et que je suis en mode création
            if(!$team && $newTeam) {
                $this->error('Équipe introuvable');
                return $this->redirect('/admin/team');
            }

            //Remplissage de l'équipe(hydrate)
            $team->fill($dataTeam);

            //Enregistrement en BDD
            if($newTeam || $team->hasChanged()) {
                if(!$this->tm->save($team)){
                    $this->error(implode('<br>',$this->tm->errors()));
                    return $this->redirect('/admin/team');
                }
            }

            //Récupération de l'ID
            if($newTeam) {
                $team->id = $this->tm->getInsertID();
            }

            //Gestion des coachs
            //Récupération des coachs actuels
            $currentCoachs = array_column($this->coachm->getCoachesByIdTeam($id),'id_member');

            //Suppression des coachs actuels et enregistrement des nouveaux
            if(empty($coachs) || $currentCoachs!=$coachs) {
                $this->coachm->where('id_team', $team->id)->delete();
                foreach ($coachs as $coach) {
                    $dataCoach = [
                        'id_member' => intval($coach),
                        'id_team' => $team->id,
                    ];

                    $this->coachm->insert($dataCoach);
                }
            }

            //Gestion des joueurs
            //Récupération des joueurs actuels
            $currentPlayers = array_column($this->playerm->getPlayersByIdTeam($id),'id_member');

            //Suppression des joueurs actuels et enregistrement des nouveaux
            if(empty($players) || $currentPlayers!=$players) {
                $this->playerm->where('id_team', $team->id)->delete();
                foreach ($players as $player) {
                    $dataPlayer = [
                        'id_member'=>intval($player),
                        'id_team' => $team->id,
                    ];
                    $this->playerm->insert($dataPlayer);
                }
            }


            //Récupération ID et gestion des messages de validation
            if($newTeam) {
                $this->success('Équipe créée avec succès');
            } else {
                $this->success('Équipe modifiée avec succès');
            }

            return $this->redirect('/admin/team');

        } catch(\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function switchActiveTeam($idTeam){

        $team = $this->tm->withDeleted()->find($idTeam);

        //Test pour savoir si le club existe
        if(!$team) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Équipe introuvable'
            ]);
        }

        // Si le membre est actif, on le désactive
        if(empty($team->deleted_at)) {
            $this->tm->delete($idTeam);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Équipe désactivée',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->tm->reactiveTeam($idTeam)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Équipe activée',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                ]);
            }
        }
    }

    public function searchTeam(){
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
        $result = $this->tm->quickSearchForSelect2($search, $page, $limit, 'name', 'ASC');

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
