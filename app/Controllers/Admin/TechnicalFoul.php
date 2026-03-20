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
}
