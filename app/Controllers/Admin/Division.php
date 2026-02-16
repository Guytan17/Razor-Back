<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\DivisionModel;
use App\Models\SeasonModel;
use CodeIgniter\HTTP\ResponseInterface;

class Division extends AdminController
{
    protected $dm;
    protected $sm;
    protected $cm;

    public function __construct(){
        $this->dm = new DivisionModel();
        $this->sm = new SeasonModel();
        $this->cm = new CategoryModel();
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
            $dataLeague =[
                'name' => $this->request->getPost('name'),
                'id_season' => $this->request->getPost('id_season'),
                'id_category' => $this->request->getPost('id_category'),
            ];
            if ($this->dm->insert($dataLeague)) {
                $this->success('Championnat créé avec succès');
            } else {
                foreach ($this->dm->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/division');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
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

    public function switchActiveLeague($idLeague){

        $league = $this->lm->withDeleted()->find($idLeague);
        //Test pour savoir si le championnat existe

        if(!$league) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Championnat introuvable'
            ]);
        }
        // Si le championnat est actif, on le désactive
        if(empty($league['deleted_at'])) {
            $this->lm->delete($idLeague);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Championnat désactivé',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->lm->reactiveLeague($idLeague)){
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
}
