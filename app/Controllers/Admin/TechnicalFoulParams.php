<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassificationModel;
use App\Models\TypeModel;
use CodeIgniter\HTTP\ResponseInterface;

class TechnicalFoulParams extends AdminController
{
    protected $typeModel ;
    protected $classificationModel ;

    public function __construct(){
        $this->typeModel = new TypeModel();
        $this->classificationModel = new ClassificationModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Fautes techniques - paramètres'
        ];
        $this->addBreadcrumb('Fautes techniques - paramètres','');
        return $this->render('admin/technical-fouls-params',$data);
    }

    public function insertType() {
        try {
            //Récupération des données
            $dataType = [
                'code' => $this->request->getPost('code_type'),
                'explanation' => $this->request->getPost('explanation_type'),
            ];

            if ($this->typeModel->insert($dataType)) {
                $this->success('Type de faute technique créé avec succès');
            } else {
                foreach ($this->typeModel->errors() as $error) {
                    $this->error($error);
                }
            }

            return $this->redirect('admin/technical-foul-params');

        } catch (\Exception $e){
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function insertClassification() {
        try {
            //Récupération des données
            $dataClassification = [
                'code' => $this->request->getPost('code_classification'),
                'explanation' => $this->request->getPost('explanation_classification'),
            ];

            if ($this->classificationModel->insert($dataClassification)) {
                $this->success('Classification de faute technique créée avec succès');
            } else {
                foreach ($this->classificationModel->errors() as $error) {
                    $this->error($error);
                }
            }

            return $this->redirect('admin/technical-foul-params');

        } catch (\Exception $e){
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updateType($id) {
        try{
            // Récupération des données
            $dataType = [
                'code' => $this->request->getPost('codeType'),
                'explanation' => $this->request->getPost('explanationType'),
            ];

            if($this->typeModel->update($id,$dataType)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Type de faute technique modifié avec succès',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->typeModel->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updateClassification($id) {
        try{
            // Récupération des données
            $dataClassification = [
                'code' => $this->request->getPost('codeClassification'),
                'explanation' => $this->request->getPost('explanationClassification'),
            ];

            if($this->classificationModel->update($id,$dataClassification)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Classification de faute technique modifié avec succès',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->classificationModel->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteType($id) {
        try {
            if($this->typeModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le type a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->typeModel->errors(),
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteClassification($id) {
        try {
            if($this->classificationModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'La classification a bien été supprimée'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->classificationModel->errors(),
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
