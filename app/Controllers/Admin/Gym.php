<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Gym extends AdminController
{
    public function index()
    {
        $this->addBreadcrumb('Liste des gymnases');
        $data = [
            'title' => 'Gymnases',
        ];
        return $this->render('admin/gym/index',$data);
    }

    public function form() {

        $this->addBreadcrumb('Listes des gymnases', '/admin/gym');
        $data = [
            'title' => 'Ajout d\'un gymnase',
        ];

        return $this->render('admin/gym/form', $data);
    }
}
