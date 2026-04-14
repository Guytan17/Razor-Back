<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\CoachModel;
use App\Models\DivisionModel;
use App\Models\DivisionTeamModel;
use App\Models\GameModel;
use App\Models\PlayerModel;
use App\Models\TeamModel;
use App\Models\SeasonModel;
use App\Models\ClubModel;
use CodeIgniter\HTTP\ResponseInterface;

class Team extends AdminController
{
    protected $teamModel;
    protected $seasonModel;
    protected $clubModel;
    protected $categoryModel;
    protected $coachModel;
    protected $playerModel;
    protected $divisionModel;
    protected $divisionTeamModel;
    protected $gameModel;

    public function __construct(){
        $this->teamModel = new TeamModel();
        $this->clubModel = new ClubModel();
        $this->categoryModel = new CategoryModel();
        $this->seasonModel = new SeasonModel();
        $this->coachModel = new CoachModel();
        $this->playerModel = new PlayerModel();
        $this->divisionModel = new DivisionModel();
        $this->divisionTeamModel = new DivisionTeamModel();
        $this->gameModel = new GameModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Équipes',
        ];
        return $this->render('admin/team/index',$data);
    }

    public function form($id=null) {
        $this->addBreadcrumb('Liste des équipes','admin/team');
        $seasons = $this->seasonModel->findAll();
        $clubs = $this->clubModel->findAll();
        $categories = $this->categoryModel->findAll();

        if($id != null) {
            $title = 'Modifier une équipe';
            $this->addBreadcrumb($title);
            $team = $this->teamModel->withDeleted()->find($id);
            $team->coachs = $this->coachModel->getCoachesByIdTeam($id);
            $team->players = $this->playerModel->getPlayersByIdTeam($id);
            $team->divisions = $this->divisionModel->getDivisionsByTeam($id);
            $team->games = $this->gameModel->getGamesByTeam($id);
            foreach ($team->games as $game) {
                if($game->home_team == $id) {
                    $game->opponent_team_id = $game->away_team;
                    $game->opponent_team_name = $game->away_team_name;
                    $game->opponent_club_id = $game->away_club_id;
                    $game->opponent_club_name = $game->away_club_name;
                } else if($game->away_team == $id) {
                    $game->opponent_team_id = $game->home_team;
                    $game->opponent_team_name = $game->home_team_name;
                    $game->opponent_club_id = $game->home_club_id;
                    $game->opponent_club_name = $game->home_club_name;
                }
            }

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
            $divisions = $this->request->getPost('divisions') ?? [];

            //Préparation de la variable pour savoir si c'est une création
            $newTeam = empty($dataTeam['id']);

            //Création de l'objet Team
            $team = $newTeam ? new \App\Entities\Team() : $this->teamModel->withDeleted()->find($id);

            //Si je n'ai pas d'équipe et que je suis en mode création
            if(!$team && $newTeam) {
                $this->error('Équipe introuvable');
                return $this->redirect('/admin/team');
            }

            //Remplissage de l'équipe(hydrate)
            $team->fill($dataTeam);

            //Enregistrement en BDD
            if($newTeam || $team->hasChanged()) {
                if(!$this->teamModel->save($team)){
                    return redirect()->back()->withInput()->with('error',implode('<br>',$this->teamModel->errors()));
                }
            }

            //Récupération de l'ID
            if($newTeam) {
                $team->id = $this->teamModel->getInsertID();
            }

            //Gestion des coachs
            //Récupération des coachs actuels
            $currentCoachs = array_column($this->coachModel->getCoachesByIdTeam($id),'id_member');

            //Suppression des coachs actuels et enregistrement des nouveaux
            if(empty($coachs) || $currentCoachs!=$coachs) {
                $this->coachModel->where('id_team', $team->id)->delete();
                foreach ($coachs as $coach) {
                    $dataCoach = [
                        'id_member' => intval($coach),
                        'id_team' => $team->id,
                    ];

                    $this->coachModel->insert($dataCoach);
                }
            }

            //Gestion des joueurs
            //Récupération des joueurs actuels
            $currentPlayers = array_column($this->playerModel->getPlayersByIdTeam($id),'id_member');

            //Suppression des joueurs actuels et enregistrement des nouveaux
            if(empty($players) || $currentPlayers!=$players) {
                $this->playerModel->where('id_team', $team->id)->delete();
                foreach ($players as $player) {
                    $dataPlayer = [
                        'id_member'=>intval($player),
                        'id_team' => $team->id,
                    ];
                    $this->playerModel->insert($dataPlayer);
                }
            }

            //Gestion des championnats
            if(!empty($divisions)) {
                $this->divisionTeamModel->where('id_team', $team->id)->delete();
                foreach($divisions as $division) {
                    $dataDivisionTeam = [
                        'id_team' => $team->id,
                        'id_division' => $division,
                    ];
                    if(!$this->divisionTeamModel->insert($dataDivisionTeam)) {
                        $this->error(implode('<br>',$this->divisionTeamModel->errors()));
                    }
                }

            }


            //Gestion des messages de validation
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

        $team = $this->teamModel->withDeleted()->find($idTeam);

        //Test pour savoir si le club existe
        if(!$team) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Équipe introuvable'
            ]);
        }

        // Si le membre est actif, on le désactive
        if(empty($team->deleted_at)) {
            $this->teamModel->delete($idTeam);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Équipe désactivée',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->teamModel->reactiveTeam($idTeam)){
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

        // Récupération des conditions dynamiques
        $conditions = [];
        $idClub = $request->getGet('id_club');

        if ($idClub !== null) {
            $conditions['id_club'] = (int) $idClub;
        }

        //Utilisation de la méthode du Model (via le trait)
        $result = $this->teamModel->searchTeamWithInfos(
            search: $search,
            page: $page,
            limit: $limit,
            conditions: $conditions
        );

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
