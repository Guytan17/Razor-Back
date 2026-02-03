<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class Category extends AdminController
{

    protected $cm;

    public function __construct(){
        $this->cm = new CategoryModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Catégories',
        ];
        $this->addBreadcrumb('Catégories', '');
        return $this->render('admin/category',$data);
    }
}
