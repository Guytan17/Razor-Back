<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClubModel;
use CodeIgniter\HTTP\ResponseInterface;

class Club extends AdminController
{
    protected $cm;

    public function __construct(){
        $this->cm = new ClubModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Clubs',
        ];
        $this->addBreadcrumb('admin/club', '');
        return $this->render('admin/club/index',$data);
    }

    public function form() {
        $data = [
            'title' => 'Ajout d\'un Club',
        ];
        $this->addBreadcrumb('Liste des clubs', 'admin/club/index');
        return $this->render('admin/club/form', $data);
    }

    public function saveClub($id=null) {
        try {
            //Récupération des données
            $dataClub =[
                'id' => $id,
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'color_1' => $this->request->getPost('color_1'),
                'color_2' => $this->request->getPost('color_2'),
            ];

            //Préparation de la variable pour savoir si c'est une création
            $newClub = empty($dataClub['id']);

            //Enregistrement en BDD
            if(!$this->cm->save($dataClub)){
                $this->error(implode('<br>',$this->cm->errors()));
                return $this->redirect('/admin/member');
            }

            //Récupération de l'ID et gestion des messages de validation
            if($newClub){
                $id = $this->cm->getInsertID();
                $this->success('Club créé avec succès');
            } else {
                $this->success('Club modifié avec succès');
            }

            return $this->redirect('/admin/club');

        } catch (\Exception $e){
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
