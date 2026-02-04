<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SeasonModel;
use CodeIgniter\HTTP\ResponseInterface;

class Season extends AdminController
{
    protected $sm;

    public function __construct(){
        $this->sm = new SeasonModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Saison',
        ];
        $this->addBreadcrumb('Saison','');
        return $this->render('admin/season',$data);
    }

    public function insertSeason(){
        try {
            $dataSeason = [
                'name' => $this->request->getPost('name'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
            ];
            if($this->sm->insert($dataSeason)){
                $this->success('Saison créée avec succès');
            } else {
                foreach ($this->sm->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/season');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return redirect()->back()->withInput();
        }
    }
}
