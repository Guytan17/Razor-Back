<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\RoleModel;
use App\Models\LicenseCodeModel;
use App\Models\RoleMemberModel;
use CodeIgniter\HTTP\ResponseInterface;

class Member extends AdminController
{

    protected $mm;
    protected $rm;
    protected $lcm;
    protected $rmm;

    public function __construct(){
        $this->mm = new MemberModel();
        $this->rm = new RoleModel();
        $this->lcm = new LicenseCodeModel();
        $this->rmm = new RoleMemberModel();
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
            $title = 'Modifier un membre';
            $this->addBreadcrumb('Modifier un membre');
            //Récupération des données pour l'édition
            $member = $this->mm->withDeleted()->find($id);
        } else {
            $title = 'Ajouter un membre';
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

    public function saveMember($id=null){
        try {
            //Récupération des données principales
            $dataMember = [
                'id' => $id,
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'id_license_code' => $this->request->getPost('license_code'),
                'balance' => $this->request->getPost('balance'),
            ];
            //Gestion du statut de la licence
             $license_status = $this->request->getPost('license_status');
             if($license_status === "on"){
                 $dataMember['license_status'] = 1;
             } else {
                 $dataMember['license_status'] = 0;
             }

            //Récupération des rôles
            $roles = $this->request->getPost('roles');

            // Récupération des données de contact
            $dataContact = [

            ];

            //Gérer Équipes (coach et joueurs)


            //préparation de la variable pour savoir si c'est une création
            $newMember = empty($dataMember['id']);

            //Création de l'objet member
            $member = $newMember ? new \App\Entities\Member() : $this->mm->withDeleted()->find($id);


            //Si je n'ai pas de membre et que je ne suis pas en mode création
            if(!$member && !$newMember) {
                $this->error('Membre introuvable');
                return $this->redirect('/admin/member');
            }

            //Remplissage du membre (hydrate)
            $member->fill($dataMember);

            //Enregistrement en BDD
            if(!$this->mm->save($member)){
                $this->error(implode('<br>',$this->mm->errors()));
            }

            //On récupère l'ID si c'est une création pour les tables d'asso
            if($newMember) {
                $member->id = $this->mm->getInsertID();
            }
            //Suppression des rôles existants en cas de modif
//            $existingRole= $this->rmm->getRoleMember($dataRole['id_member']);
//            if($existingRole) {
//
//            }
            //Gestion des rôles
            if(isset($roles)) {
                $this->rmm->where('id_member', $id)->delete();
                foreach($roles as $role) {
                    $dataRole = [
                        'id_member' => intval($id),
                        'id_role' => intval($role)
                    ];
                    $this->rmm->insert($dataRole);
                }
            }

            // Gestion des messages de validation
            if($newMember){
                $this->success('Membre créé avec succès');
            } else {
                $this->success('Membre modifié avec succès');
            }

            return $this->redirect('admin/member');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function switchActiveMember($idMember){

        $member = $this->mm->withDeleted()->find($idMember);

        //Test pour savoir si le club existe
        if(!$member) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Membre introuvable'
            ]);
        }

        // Si le membre est actif, on le désactive
        if(empty($member->deleted_at)) {
            $this->mm->delete($idMember);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Membre désactivé',
            ]);
        } else {
            //S'il est inactif, on le réactive
            if($this->mm->reactiveMember($idMember)){
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Membre activé',
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
