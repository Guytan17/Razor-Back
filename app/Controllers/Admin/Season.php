<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Season extends AdminController
{
    public function index()
    {
        $data = [
            'title' => 'Saison',
        ];
        $this->addBreadcrumb('Saison','');
        return $this->render('admin/season',$data);
    }
}
