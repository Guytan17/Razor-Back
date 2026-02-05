<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LicenseCodeModel;
use CodeIgniter\HTTP\ResponseInterface;

class LicenseCode extends AdminController
{
    protected $lcm;

    public function __construct(){
        $this->lcm = new LicenseCodeModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Codes licences'
        ];
        $this->addBreadcrumb('Codes licences','');
        return $this->render('admin/license-code',$data);
    }

    public function insertLicenseCode() {
        try {
            $dataLicenseCode = [
                'code' => $this->request->getPost('code'),
                'explanation' => $this->request->getPost('explanation'),
            ];
            if ($this->lcm->insert($dataLicenseCode)) {
                $this->success('Code licence créé avec succès');
            } else {
                foreach ($this->lcm->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/license-code');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return redirect()->back()->withInput();
        }
    }

    public function updateLicenseCode($id) {
        try{
            // Récupération des données
            $dataLicenseCode = [
                'code' => $this->request->getPost('code'),
                'explanation' => $this->request->getPost('explanation'),
            ];

            if($this->lcm->update($id,$dataLicenseCode)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Code licence modifié avec succès',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->lcm->errors(),
                ]);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteLicenseCode($id) {
        try {
            if($this->lcm->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le code license a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->lcm->errors(),
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
