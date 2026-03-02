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
        $this->addBreadcrumb('Liste des clubs', '');
        return $this->render('admin/club/index',$data);
    }

    public function form($id=null) {
        $this->addBreadcrumb('Liste des clubs', 'admin/club');
        if($id != null) {
            $title = 'Modifier un club';
            $this->addBreadcrumb('Modifier un club');
            $club = $this->cm->find($id);
        } else {
            $title = 'Ajouter un club';
            $this->addBreadcrumb('Ajouter un club');
        }
        $data = [
            'title' => $title,
            'club' => $club ?? null,
        ];
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

    public function switchActiveClub($idClub){

        $club = $this->cm->withDeleted()->find($idClub);

        //Test pour savoir si le club existe
        if(!$club) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Club introuvable'
            ]);
        }

        // Si le club est actif, on le désactive
        if(empty($club['deleted_at'])) {
            $this->cm->delete($idClub);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Club désactivé',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->cm->reactiveClub($idClub)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Club activé',
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                ]);
            }
        }
    }
    public function searchClub(){
        $request = $this->request;

        //Vérification Ajax
        if(!$request->isAJAX()) {
            return $this->response->setJSON(['error'=> 'Requête non autorisée']);
        }

        //Paramètres de recherche
        $search = $request->getget('search') ?? '';
        $page = (int) $request->getget('page') ?? 1;
        $limit = 25;

        //Utilisation de la méthode du Model (via le trait)
        $result = $this->cm->quickSearchForSelect2($search, $page, $limit, 'name', 'ASC');

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
