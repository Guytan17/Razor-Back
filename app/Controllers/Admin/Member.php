<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Member extends AdminController
{
    public function index()
    {
        $data = [
            'title' => 'Membres du club',
        ];
        $this->addBreadcrumb('Membres du club','');
        return $this->render('admin/member/index', $data);
    }

    public function form () {
        $data = [
            'title' => 'Ajout d\'un membre'
        ];
        $this->addBreadcrumb('Liste des membres','/admin/member');
        return $this->render('admin/member/form',$data);
    }
}
