<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LicenseCode extends AdminController
{
    public function index()
    {
        $data = [
            'title' => 'Codes licences'
        ];
        $this->addBreadcrumb('Codes licences','');
        return $this->render('admin/license-code',$data);
    }
}
