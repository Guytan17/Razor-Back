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
}
