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
}
