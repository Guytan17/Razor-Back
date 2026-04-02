<?php

namespace App\Controllers\Admin;

use App\Models\CategoryModel;
use App\Models\DivisionModel;
use App\Models\DivisionTeamModel;
use App\Models\SeasonModel;

class Division extends AdminController
{
    protected $dm;
    protected $sm;
    protected $cm;
    protected $divisionTeamModel;

    public function __construct(){
        $this->dm = new DivisionModel();
        $this->sm = new SeasonModel();
        $this->cm = new CategoryModel();
        $this->divisionTeamModel = new DivisionTeamModel();
    }

    public function index()
    {
        $seasons = $this->sm->getAllSeasons();
        $categories = $this->cm->getAllCategories();
        $data =[
            'title' => 'Championnats',
            'seasons' => $seasons,
            'categories' => $categories
        ];
        $this->addBreadcrumb('Championnats');
        return $this->render('admin/division',$data);
    }

    public function insertDivision () {
        try {
            //Récupération des données
            $dataDivision =[
                'name' => $this->request->getPost('name'),
                'id_season' => $this->request->getPost('id_season'),
                'id_category' => $this->request->getPost('id_category'),
            ];
            if ($this->dm->insert($dataDivision)) {
                $this->success('Championnat créé avec succès');
            } else {
                return redirect()->back()->withInput()->with('error',implode('<br>',$this->dm->errors()));
            }
            return $this->redirect('admin/division');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updateDivision ($id) {
        try{
            // Récupération des données
            $dataDivision=[
                'name'=>$this->request->getPost('name'),
                'id_season'=>$this->request->getPost('id_season'),
                'id_category'=>$this->request->getPost('id_category'),
            ];
            $teams = $this->request->getPost('teams');

            //gestion des équipes liées au championnat
            $this->divisionTeamModel->where('id_division', $id)->delete();
            if(!empty($teams)) {
                foreach($teams as $team) {
                    $dataDivisionTeam = [
                        'id_team' => $team,
                        'id_division' => $id,
                    ];
                    if(!$this->divisionTeamModel->insert($dataDivisionTeam)) {
                        $this->error(implode('<br>',$this->divisionTeamModel->errors()));
                    }
                }
            }

            if($this->dm->update($id,$dataDivision)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Championnat modifié avec succès',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->dm->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function switchActiveDivision($idDivision){

        $division = $this->dm->withDeleted()->find($idDivision);
        //Test pour savoir si le championnat existe

        if(!$division) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Championnat introuvable'
            ]);
        }
        // Si le championnat est actif, on le désactive
        if(empty($division['deleted_at'])) {
            $this->dm->delete($idDivision);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Championnat désactivé',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->dm->reactiveDivision($idDivision)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Championnat activé',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                ]);
            }
        }
    }
    public function searchDivision(){
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
        $result = $this->dm->searchWithSeasonNameAndCategoryName($search, $page, $limit);

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
