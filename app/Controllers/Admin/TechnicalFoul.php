<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TechnicalFoulModel;
use CodeIgniter\HTTP\ResponseInterface;

class TechnicalFoul extends AdminController
{
    protected $technicalFoulModel;

    public function __construct(){
        $this->technicalFoulModel = new TechnicalFoulModel();
    }
    public function index()
    {
        $title = 'Fautes techniques';
        $this->addBreadCrumb($title);

        $data = [
            'title' => $title,
        ];
        return $this->render('admin/technical-foul',$data);
    }

    public function insertTechnicalFoul(){
        try {
            $dataTF = [
                'id_game' => $this->request->getPost('id_game'),
                'id_member' => $this->request->getPost('id_member'),
                'id_type' => $this->request->getPost('id_type'),
                'id_classification' => $this->request->getPost('id_classification'),
                'amount' => $this->request->getPost('amount'),
            ];

            if($this->technicalFoulModel->insert($dataTF)){
                $this->success('Faute technique créée avec succès');
                return $this->redirect('/admin/technical-foul');
            } else {
                $this->error(implode('<br>',$this->technicalFoulModel->errors()));
                return $this->redirect('/admin/technical-foul');
            }



        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function updateTechnicalFoul($id){
        try{
            // Récupération des données
            $dataTechnicalFoul=[
                'id_game'=>$this->request->getPost('id_game'),
                'id_member'=>$this->request->getPost('id_member'),
                'id_type'=>$this->request->getPost('id_type'),
                'id_classification'=>$this->request->getPost('id_classification'),
                'amount' => $this->request->getPost('amount'),
            ];

            if($this->technicalFoulModel->update($id,$dataTechnicalFoul)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Faute technique modifiée avec succès',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->technicalFoulModel->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteTechnicalFoul($id) {
        try {
            if($this->technicalFoulModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'La faute technique a bien été supprimée'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->technicalFoulModel->errors(),
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
