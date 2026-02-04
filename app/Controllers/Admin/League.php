<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class League extends AdminController
{
    public function index()
    {
        $data =[
            'title' => 'Championnats',
        ];
        $this->addBreadcrumb('Championnats');
        return $this->render('admin/league',$data);
    }
}
