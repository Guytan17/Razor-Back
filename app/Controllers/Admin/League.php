<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\LeagueModel;
use App\Models\SeasonModel;
use CodeIgniter\HTTP\ResponseInterface;

class League extends AdminController
{
    protected $lm;
    protected $sm;
    protected $cm;

    public function __construct(){
        $this->lm = new LeagueModel();
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
        return $this->render('admin/league',$data);
    }

    public function insertLeague () {
        try {
            //Récupération des données
            $dataLeague =[
                'name' => $this->request->getPost('name'),
                'id_season' => $this->request->getPost('id_season'),
                'id_category' => $this->request->getPost('id_category'),
            ];
            if ($this->lm->insert($dataLeague)) {
                return $this->success('Championnat créé avec succès');
            } else {
                foreach ($this->lm->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/league');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return redirect()->back()->withInput();
        }
    }
}
