<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Club extends AdminController
{
    public function index()
    {
        $data = [
            'title' => 'Clubs',
        ];
        $this->addBreadcrumb('admin/club', '');
        return $this->render('admin/club/index',$data);
    }

    public function form() {
        $data = [
            'title' => 'Ajout d\'un Club',
        ];
        $this->addBreadcrumb('Liste des clubs', 'admin/club/index');
        return $this->render('admin/club/form', $data);
    }
}
