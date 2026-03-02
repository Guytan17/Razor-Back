<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AddressModel;
use App\Models\GymClubModel;
use App\Models\GymModel;
use CodeIgniter\HTTP\ResponseInterface;

class Gym extends AdminController
{
    protected $addressModel;
    protected $gymModel;
    protected $gymClubModel;
    public function __construct(){
        $this->addressModel = new AddressModel();
        $this->gymModel = new GymModel();
        $this->gymClubModel = new GymClubModel();
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

        if ($id != null) {
            $title = 'Ajout d\'un gymnase';
            $this->addBreadcrumb('Ajouter un gymnase');
            $gym = $this->gymModel->getGymById($id);
            $gym['clubs']= $this->gymClubModel->getClubsByIdGym($id);
        } else {
            $title = 'Modification d\'un gymnase';
            $this->addBreadcrumb('Modifier un gymnase');
        }
        $data = [
            'title' => $title,
            'gym' => $gym ?? null,
        ];

        return $this->render('admin/gym/form', $data);
    }

    public function saveGym($id=null){
        try {
            //Récupération des données
            //Données concernant le gymnase
            $dataGym = [
                'id' => $id,
                'fbi_code' => $this->request->getPost('fbi_code'),
                'name' => $this->request->getPost('name'),
                'id_address' => intval($this->request->getPost('id_address')) ?? null
            ];

            //Données concernant l'adresse
            $dataAddress = [
                'id' => $dataGym['id_address'],
                'address_1' => $this->request->getPost('address_1') ?? '',
                'address_2' => $this->request->getPost('address_2') ?? '',
                'id_city' => $this->request->getPost('city') ?? '',
                'gps_location' => $this->request->getPost('gps_location') ?? '',
            ];

            //Données concernant le club
            $clubs = $this->request->getPost('clubs') ?? [];
            //Variable pour savoir si c'est un nouveau gymnase
            $newGym = empty($dataGym['id']);

            //Enregistrement de l'adresse et récupération de l'ID de la nouvelle adresse
            if(!$this->addressModel->save($dataAddress)){
                $this->error(implode('<br>',$this->addressModel->errors()));
                return $this->redirect('/admin/gym');
            } elseif ($newGym){
                $dataGym['id_address'] = $this->addressModel->getInsertID();
            }

            //Enregistrement du gymnase
            if(!$this->gymModel->saveGym($dataGym)){
                $this->error(implode('<br>',$this->gymModel->errors()));
                return $this->redirect('/admin/gym');
            } elseif ($newGym){
                $dataGym['id'] = $this->gymModel->getInsertID();
            }

            //Enregistrement du club
            //Création des variables
            $existingClubs = $this->gymClubModel->where('id_gym', $dataGym['id'])->findAll();
            $existingClubsIndexed = array_column($existingClubs, null,'id_club');
            $clubsIds = array_column($clubs,'id');

            //On supprime les clubs qui ont été retirés
            foreach ($existingClubs as $existingClub){
                if(!in_array($existingClub['id_club'], $clubsIds)){
                    $this->gymClubModel
                        ->where([
                            'id_gym'=>$dataGym['id'],
                            'id_club'=>$existingClub['id_club']
                        ])
                    ->delete();
                }
            }

            //On boucle sur les clubs envoyés dans le formulaire
            foreach ($clubs as $club) {
                //Variable du club envoyé dans le formulaire
                $dataGymClub = [
                    'id_club' => $club['id'],
                    'id_gym' => $dataGym['id'],
                    'main_gym' => isset($club['main_gym']) ? 1 : 0,
                ];

                //Enregistrement du club s'il n'existe pas déjà
                if(!isset($existingClubsIndexed[$club['id']])){
                    $this->gymClubModel->insert($dataGymClub);
                }
                //sinon, s'il existe, on compare le champ main_gym et le met à jour si besoin
                else {
                    if($existingClubsIndexed[$club['id']]['main_gym'] != $dataGymClub['main_gym']){
                        $this->gymClubModel->where('id_club',$dataGymClub['id_club'])->where('id_gym',$dataGymClub['id_gym'])->update(null, $dataGymClub);
                    }
                }
            }

//            if (isset ($existingClubs)) {
//                foreach ($existingClubs as $existingClub) {
//                    if (!in_array($existingClub, $clubs)) {
//                        $this->gymClubModel->delete($existingClub);
//                    }
//                }
//            }

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

    //fonction à faire sur chaque itération du tableau
    private function InsertOrDeleteClub ($existingClubs,$data) {
        foreach ($existingClubs as $club) {

        }
        return ;
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
