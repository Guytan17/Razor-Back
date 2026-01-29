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
}
