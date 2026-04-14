<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use CodeIgniter\HTTP\ResponseInterface;

class Role extends AdminController
{
    protected $roleModel;

    public function __construct(){
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Rôles',
            ];
        $this->addBreadcrumb('Rôles', '');
        return $this->render('admin/role',$data);
    }

    public function insertRole () {
        try{
            // Récupération des données
            $dataRole=[
                'name'=>$this->request->getPost('name'),
            ];
            if ($this->roleModel->insert($dataRole)){
                $this->success('Rôle créé avec succès');
            } else {
                return redirect()->back()->withInput()->with('error',implode('<br>',$this->roleModel->errors()));
            }
            return $this->redirect('admin/role');
        } catch(\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updateRole($id) {
        try{
            // Récupération des données
            $dataRole=[
                'name'=>$this->request->getPost('name'),
            ];

            if($this->roleModel->update($id,$dataRole)){
               return $this->response->setJSON([
                   'success' => true,
                   'message' => 'Rôle modifié avec succès',
               ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->roleModel->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteRole($id) {
        try {
            if($this->roleModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le rôle a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->roleModel->errors(),
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
