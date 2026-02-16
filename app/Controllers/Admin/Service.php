<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
use CodeIgniter\HTTP\ResponseInterface;

class Service extends AdminController
{
    protected $sm;

    public function __construct(){
        $this->sm = new ServiceModel();
    }
    public function index()
    {
        $title = 'Liste des services';
        $this->addBreadcrumb($title);

        $data = [
            'title' => $title
        ];
        return $this->render('admin/service',$data);
    }

    public function insertService () {
        try{
            // Récupération des données
            $dataService=[
                'label'=>$this->request->getPost('label'),
            ];
            if ($this->sm->insert($dataService)){
                $this->success('Service créé avec succès');
            } else {
                foreach ($this->sm->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/service');
        } catch(\Exception $e) {
            $this->error = $e->getMessage();
            return redirect()->back()->withInput();
        }
    }

    public function updateService($id) {
        try{
            // Récupération des données
            $dataService=[
                'label'=>$this->request->getPost('label'),
            ];

            if($this->sm->update($id,$dataService)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service modifié avec succès',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->sm->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteService($id) {
        try {
            if($this->sm->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le service a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->sm->errors(),
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
