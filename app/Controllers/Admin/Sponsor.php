<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SponsorModel;
use CodeIgniter\HTTP\ResponseInterface;

class Sponsor extends AdminController
{
    protected $sponsorModel;

    public function __construct() {
        $this->sponsorModel = new SponsorModel();
    }
    public function index()
    {
        $title = 'Sponsors';
        $this->addBreadCrumb('Liste des sponsors');
        $data = [
            'title' => $title,
        ];
        return $this->render('admin/sponsor', $data);
    }

    public function insertSponsor(){
        try {
            $dataSponsor = [
                'name' => $this->request->getPost('name'),
                'rank' => $this->request->getPost('rank'),
                'specifications' => $this->request->getPost('specifications'),
            ];
            $logo = $this->request->getFile('logo');

            if(!$this->sponsorModel->insert($dataSponsor)){
                $this->success('Sponsor créé avec succès');
            } else {
                foreach ($this->sponsorModel->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/sponsor');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return redirect()->back()->withInput();
        }
    }

    public function updateSponsor($id){
        try {
            //récupération des données
            $dataSponsor = [
                'name' => $this->request->getPost('name'),
                'rank' => $this->request->getPost('rank'),
                'specifications' => $this->request->getPost('specifications'),
            ];
            //récupération de l'image
            $logo = $this->request->getFile('logo');
           log_message('debug', 'print_r de logo : '.print_r($logo,true));

            //si les specifications sont supprimées, on les force en null
            $dataSponsor['specifications'] = empty($dataSponsor['specifications']) ? null : $dataSponsor['specifications'];

            //Gestion du logo
            $dataLogo = [
                'entity_id' => $id,
                'entity_type' => 'sponsor',
                'title' => 'Logo de ' . $dataSponsor['name'],
                'alt' => 'Logo de ' . $dataSponsor['name'],
            ];
            $uploadResultLogo = upload_file($logo,'logos/sponsor/'.$id, $logo->getName(),$dataLogo,false);
            if(is_array($uploadResultLogo) && isset($uploadResultLogo['status']) && $uploadResultLogo['status'] == 'error'){
                $this->error("Erreur lors de l'upload du logo :".$uploadResultLogo['message']);
            }

            if($this->sponsorModel->update($id,$dataSponsor)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Sponsor modifié avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->sponsorModel->errors()
                ]);
            }
        } catch (\Exception $e){
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteSponsor($id) {
        try {
            if($this->sponsorModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le sponsor a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->sponsorModel->errors(),
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
