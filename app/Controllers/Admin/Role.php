<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Role extends AdminController
{
    public function index()
    {
        $data = [
            'title' => 'Rôles',
            ];
        $this->addBreadcrumb('Rôles', '');
        return $this->render('admin/role',$data);
    }
}
