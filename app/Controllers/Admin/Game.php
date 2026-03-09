<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClubModel;
use CodeIgniter\HTTP\ResponseInterface;

class Game extends AdminController
{
    protected $clubModel;

    public function __construct(){
        $this->clubModel = new ClubModel();
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
        $clubs = $this->clubModel->findAll();
        if ($id != null) {
            $title = 'Modifier un match';
        } else {
            $title = 'Ajouter un match';
        }
        $this->addBreadcrumb($title);
        $data = [
            'title' => $title,
            'clubs' => $clubs,
        ];
        return $this->render('admin/game/form', $data);
    }
}
