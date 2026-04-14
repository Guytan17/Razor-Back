<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
use CodeIgniter\HTTP\ResponseInterface;

class Service extends AdminController
{
    protected $serviceModel;

    public function __construct(){
        $this->serviceModel = new ServiceModel();
    }
    public function index()
    {
        $title = 'Services';
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
            if ($this->serviceModel->insert($dataService)){
                $this->success('Service créé avec succès');
            } else {
                return redirect()->back()->withInput()->with('error',implode('<br>',$this->serviceModel->errors()));
            }
            return $this->redirect('admin/service');
        } catch(\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updateService($id) {
        try{
            // Récupération des données
            $dataService=[
                'label'=>$this->request->getPost('label'),
            ];

            if($this->serviceModel->update($id,$dataService)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service modifié avec succès',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->serviceModel->errors(),
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
            if($this->serviceModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le service a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->serviceModel->errors(),
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
