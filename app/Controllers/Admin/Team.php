<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Team extends AdminController
{
    public function index()
    {
        $data = [
            'title' => 'Liste des équipes',
        ];
        return $this->render('admin/team/index',$data);
    }
}
