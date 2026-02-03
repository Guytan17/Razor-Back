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

    public function insertCategory () {
        try{
            // Récupération des données
            $dataCategory=[
                'name'=>$this->request->getPost('name'),
                'gender'=>$this->request->getPost('gender'),
            ];
            if ($this->cm->insert($dataCategory)){
                $this->success('Catégorie créée avec succès');
            } else {
                foreach ($this->cm->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/category');
        } catch(\Exception $e) {
            $this->error = $e->getMessage();
            return redirect()->back()->withInput();
        }
    }

    public function updateCategory ($id) {
        try{
            $dataCategory=[
                'name'=>$this->request->getPost('name'),
                'gender'=>$this->request->getPost('gender'),
            ];
            if ($this->cm->update($id,$dataCategory)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Catégorie modifiée avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->cm->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
            'success' => false,
            'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteCategory ($id) {
        try {
            if($this->cm->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'La catégorie a bien été supprimée'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->cm->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
