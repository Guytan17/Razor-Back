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

    public function updateSeason($id){
        try {
            $dataSeason = [
                'name' => $this->request->getPost('name'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
            ];
            //si les dates de début/fin sont supprimées, on les force en null
            $dataSeason['start_date'] = empty($dataSeason['start_date']) ? null : $dataSeason['start_date'];
            $dataSeason['end_date'] = empty($dataSeason['end_date']) ? null : $dataSeason['end_date'];
            log_message('debug', print_r($dataSeason, true));
            log_message('debug', 'Updating season id: '.$id);
            if($this->sm->update($id,$dataSeason)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Saison modifiée avec succès'
                ]);
            } else {
                log_message('debug', print_r($this->sm->errors(), true));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->sm->errors()
                ]);
            }
        } catch (\Exception $e){
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteSeason($id) {
        try {
            if($this->sm->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'La saison a bien été supprimée'
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
