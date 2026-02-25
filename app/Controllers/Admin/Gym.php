<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AddressModel;
use App\Models\GymModel;
use CodeIgniter\HTTP\ResponseInterface;

class Gym extends AdminController
{
    protected $addressModel;
    protected $gymModel;
    public function __construct(){
        $this->addressModel = new AddressModel();
        $this->gymModel = new GymModel();
    }
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
                'fbi_code' => $this->request->getPost('fbi_code'),
                'name' => $this->request->getPost('name'),
                'id_address' => $this->request->getPost('id_address') ?? null,
            ];

            //Données concernant l'adresse
            $dataAddress = [
                'id' => $dataGym['id_address'],
                'address_1' => $this->request->getPost('address_1'),
                'address_2' => $this->request->getPost('address_2'),
                'id_city' => $this->request->getPost('city'),
                'gps_location' => $this->request->getPost('gps_location') ?? null,
            ];

            //Variable pour savoir si c'est un nouveau gymnase
            $newGym = empty($dataGym['id_address']);

            //Enregistrement de l'adresse et récupération de l'ID de la nouvelle adresse
            if(!$this->addressModel->save($dataAddress)){
                $this->error(implode('<br>',$this->addressModel->errors()));
                return $this->redirect('/admin/gym/form');
            } elseif ($newGym){
                $dataGym['id_address'] = $this->addressModel->getInsertID();
            }

            //Enregistrement du gymnase
            if(!$this->gymModel->save($dataGym)){
                $this->error(implode('<br>',$this->gymModel->errors()));
                return $this->redirect('/admin/gym/form');
            }

            //Gestion des messages de validation
            if ($newGym) {
                $this->success('Gymnase créé avec succès');
            } else {
                $this->success('Gymnase modifié avec succès');
            }

            return $this->redirect('admin/gym');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }

    }

    public function deleteGym($id) {
        try {
            if($this->gymModel->delete($id)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Le gymnase a bien été supprimé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->gymModel->errors(),
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
