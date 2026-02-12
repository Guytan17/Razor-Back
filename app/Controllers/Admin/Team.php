<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
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

    public function __construct(){
        $this->tm = new TeamModel();
        $this->cm = new ClubModel();
        $this->catm = new CategoryModel();
        $this->sm = new SeasonModel();

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
        } else {
            $title = 'Ajouter une équipe';
            $this->addBreadcrumb($title);
        }

        $data = [
            'title' => $title,
            'seasons' => $seasons,
            'clubs' => $clubs,
            'categories' => $categories,
        ];

        return $this->render('admin/team/form',$data);
    }

    public function saveTeam ($id=null) {
        try {
            //Récupération des données
            $dataTeam = [
                'id' => $id,
                'name' => $this->request->getPost('name'),
                'id_season' => $this->request->getPost('id_season'),
                'id_club' => $this->request->getPost('id_club'),
                'id_team' => $this->request->getPost('id_team'),
            ];

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
            if(!$this->tm->save($team)){
                $this->error(implode('<br>',$this->tm->errors()));
                return $this->redirect('/admin/team');
            }

            //Récupération ID et gestion des messages de validation
            if($newTeam) {
                $id = $this->tm->getInsertID();
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
}
