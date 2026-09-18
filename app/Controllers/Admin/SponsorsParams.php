<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DotationTypeModel;
use App\Models\SponsorRankModel;

class SponsorsParams extends AdminController
{
    protected $sponsorRankModel;
    protected $dotationTypeModel;

    public function __construct(){
        $this->sponsorRankModel = new SponsorRankModel();
        $this->dotationTypeModel = new DotationTypeModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Sponsors - paramètres'
        ];
        $this->addBreadcrumb('Sponsors - paramètres','');
        return $this->render('admin/sponsors-params',$data);
    }

    public function insertRank() {
        try {
            //Récupération des données
            $dataRank = [
                'rank' => $this->request->getPost('rank'),
                'label' => $this->request->getPost('label'),
            ];

            if ($this->sponsorRankModel->insert($dataRank)) {
                $this->success('Rang d\'importance du sponsor créé avec succès');
            } else {
                foreach ($this->sponsorRankModel->errors() as $error) {
                    $this->error($error);
                }
            }

            return $this->redirect('admin/sponsors-params');

        } catch (\Exception $e){
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function insertType() {
        try {
            //Récupération des données
            $dataType = [
                'type' => $this->request->getPost('dotation_type'),
            ];

            if ($this->dotationTypeModel->insert($dataType)) {
                $this->success('Type de dotation créé avec succès');
            } else {
                foreach ($this->dotationTypeModel->errors() as $error) {
                    $this->error($error);
                }
            }

            return $this->redirect('admin/sponsors-params');

        } catch (\Exception $e){
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
