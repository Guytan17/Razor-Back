<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TechnicalFoulParams extends AdminController
{
    public function index()
    {
        $data = [
            'title' => 'Fautes techniques - paramètres'
        ];
        $this->addBreadcrumb('Fautes techniques - paramètres','');
        return $this->render('admin/technical-fouls-params',$data);
    }
}
