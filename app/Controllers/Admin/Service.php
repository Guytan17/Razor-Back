<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Service extends AdminController
{
    public function index()
    {
        $title = 'Liste des services';
        $this->addBreadcrumb($title);

        $data = [
            'title' => $title
        ];
        return $this->render('admin/service',$data);
    }
}
