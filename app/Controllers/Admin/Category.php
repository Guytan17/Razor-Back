<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class Category extends AdminController
{

    protected $categoryModel;

    public function __construct(){
        $this->categoryModel = new CategoryModel();
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
            if ($this->categoryModel->insert($dataCategory)){
                $this->success('Catégorie créée avec succès');
            } else {
                return redirect()->back()->withInput()->with('error',implode('<br>',$this->categoryModel->errors()));
            }
            return $this->redirect('admin/category');
        } catch(\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updateCategory ($id) {
        try{
            $dataCategory=[
                'name'=>$this->request->getPost('name'),
                'gender'=>$this->request->getPost('gender'),
            ];
            if ($this->categoryModel->update($id,$dataCategory)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Catégorie modifiée avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->categoryModel->errors(),
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
            if($this->categoryModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'La catégorie a bien été supprimée'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->categoryModel->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function searchCategory(){
        $request = $this->request;

        //Vérification Ajax
        if(!$request->isAJAX()) {
            return $this->response->setJSON(['error'=> 'Requête non autorisée']);
        }

        //Paramètres de recherche
        $search = $request->getget('search') ?? '';
        $page = (int) $request->getget('page') ?? 1;
        $limit = 25;

        //Utilisation de la méthode du Model (via le trait)
        $result = $this->categoryModel->quickSearchForSelect2($search, $page, $limit, 'id', 'ASC');

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
