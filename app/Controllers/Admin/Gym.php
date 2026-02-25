<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Gym extends AdminController
{
    public function index()
    {
        $this->addBreadcrumb('Liste des gymnases');
        $data = [
            'title' => 'Gymnases',
        ];
        return $this->render('admin/gym/index',$data);
    }

    public function form($id = null) {

        $this->addBreadcrumb('Listes des gymnases', '/admin/gym');
        $data = [
            'title' => 'Ajout d\'un gymnase',
        ];

        return $this->render('admin/gym/form', $data);
    }

    public function saveGym($id=null){
        try {
            //Récupération des données
            //Données concernant le gymnase
            $dataGym = [
                'fbi_number' => $this->request->getPost('fbi_number'),
                'name' => $this->request->getPost('name'),
            ];

            //Données concernant l'adresse
            $dataAddress = [
                'address_1' => $this->request->getPost('address_1'),
                'address_2' => $this->request->getPost('address_2'),
            ];

            dd($dataGym,$dataAddress);

            return $this->redirect('admin/gym');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
