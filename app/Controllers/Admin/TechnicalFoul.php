<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TechnicalFoul extends AdminController
{
    public function index()
    {
        $title = 'Fautes techniques';
        $this->addBreadCrumb($title);

        $data = [
            'title' => $title,
        ];
        return $this->render('admin/technical-foul',$data);
    }
}
