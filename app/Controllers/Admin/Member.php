<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\RoleModel;
use App\Models\LicenseCodeModel;
use CodeIgniter\HTTP\ResponseInterface;

class Member extends AdminController
{

    protected $mm;
    protected $rm;
    protected $lcm;

    public function __construct(){
        $this->mm = new MemberModel();
        $this->rm = new RoleModel();
        $this->lcm = new LicenseCodeModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Membres du club',
        ];
        $this->addBreadcrumb('Membres du club','');
        return $this->render('admin/member/index', $data);
    }

    public function form ($id=null) {
        $this->addBreadcrumb('Liste des membres','/admin/member');
        $roles = $this->rm->findAll();
        $license_codes = $this->lcm->findAll();
        if($id != null) {
            $title = 'Ajouter un membre';
            $this->addBreadcrumb('Modifier un membre');
            //Récupération des données pour l'édition
            $member = $this->mm->withDeleted()->find($id);
        } else {
            $title = 'Modifier un membre';
            $this->addBreadcrumb('Ajouter un membre');
        }
        $data = [
            'title' => $title,
            'roles' => $roles,
            'license_codes' => $license_codes,
            'member' => $member??null,
        ];
        return $this->render('admin/member/form',$data);
    }

    public function save(){
        try {
            //Récupération des données principales
            $dataMember = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'id_role' => $this->request->getPost('role'),
                'id_license_code' => $this->request->getPost('license_code'),
                'license_statut' => $this->request->getPost('license_statut'),
                'balance' => $this->request->getPost('balance'),
            ];

            // Récupération des données de contact
            $dataContact = [

            ];

            //Création de l'objet member
            $member = new \App\Entities\Member();

            //Remplissage du membre (hydrate)
            $member->fill($dataMember);

            //Enregistrement en BDD
            if($this->mm->save($member)){
                $this->success('Membre créé avec succès');
            }

            return $this->redirect('admin/member');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function switchActiveMember($idMember){

        $member = $this->mm->withDeleted()->find($idMember);

        //Test pour savoir si l'artiste existe
        if(!$member) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Membre introuvable'
            ]);
        }

        // Si l'artiste est actif, on le désactive
        if(empty($member->deleted_at)) {
            $this->mm->delete($idMember);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Membre désactivé',
                'status' => 'inactive'
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->mm->reactiveMember($idMember)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Membre activé',
                    'status' => 'active'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                ]);
            }
        }
    }
}
