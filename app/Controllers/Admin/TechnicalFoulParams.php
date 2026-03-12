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
}
