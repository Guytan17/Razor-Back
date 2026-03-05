<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Game extends AdminController
{
    public function index()
    {
        $title = 'Matchs';
        $this->addBreadcrumb('Liste des matchs');
        $data = [
            'title' => $title,
        ];
        return $this->render('admin/game/index', $data);
    }
}
