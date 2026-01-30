<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use CodeIgniter\HTTP\ResponseInterface;

class Role extends AdminController
{
    protected $rm;

    public function __construct(){
        $this->rm = new RoleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Rôles',
            ];
        $this->addBreadcrumb('Rôles', '');
        return $this->render('admin/role',$data);
    }

    public function saveRole($id=null) {
        // Récupération des données
        $dataRole=[
            'id' => $id,
            'name'=>$this->request->getPost('name'),
        ];


        if(!$this->rm->save($dataRole)){
            $this->error(implode('<br>',$this->rm->errors()));
            return $this->redirect('/admin/role');
        }
        return $this->redirect('/admin/role');
    }
}
