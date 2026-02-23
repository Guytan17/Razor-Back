<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\PlayerModel;
use App\Models\RoleModel;
use App\Models\LicenseCodeModel;
use App\Models\RoleMemberModel;
use App\Models\CoachModel;

use CodeIgniter\HTTP\ResponseInterface;

class Member extends AdminController
{

    protected $mm;
    protected $rm;
    protected $lcm;
    protected $rmm;
    protected $coachm;
    protected $playerm;

    public function __construct(){
        $this->mm = new MemberModel();
        $this->rm = new RoleModel();
        $this->lcm = new LicenseCodeModel();
        $this->rmm = new RoleMemberModel();
        $this->coachm = new CoachModel();
        $this->playerm = new PlayerModel();
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
            $member->roles = $this->rmm->getRoleMember($id);
            $member->coach_teams = $this->coachm->getCoachesByIdMember($id);
            $member->player_teams = $this->playerm->getPlayersByIdMember($id);
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
                'first_name' => ucwords($this->request->getPost('first_name')),
                'last_name' => strtoupper($this->request->getPost('last_name')),
                'date_of_birth' => $this->request->getPost('date_of_birth'),
                'id_license_code' => $this->request->getPost('license_code'),
                'balance' => $this->request->getPost('balance'),
                'overqualified' => $this->request->getPost('overqualified'),
                'details' => $this->request->getPost('availability_details'),
            ];
            //Gestion du statut de la licence
             $license_status = $this->request->getPost('license_status');
             if($license_status === "on"){
                 $dataMember['license_status'] = 1;
             } else {
                 $dataMember['license_status'] = 0;
             }

             //Gestion de la disponibilité du membre
            $available = $this->request->getPost('available');
             if($available === "on"){
                 $dataMember['available'] = 1;
             } else {
                 $dataMember['available'] = 0;
             }

            //Récupération des rôles
            $roles = $this->request->getPost('roles');

            // Récupération des données de contact
            $contacts = $this->request->getPost('contacts');
            dd($contacts);

            //Gérer Équipes (coach et joueurs)
            $coachs = $this->request->getPost('coachs') ?? [];
            $players = $this->request->getPost('players') ?? [];

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
                        'id_member' => intval($member->id),
                        'id_role' => intval($role)
                    ];
                    $this->rmm->insert($dataRole);
                }
            }

            //Gestion des coachs
            //Récupération des coachs actuels
            $currentCoachs = array_column($this->coachm->getCoachesByIdMember($id),'id_team');

            if(empty($coachs) || $currentCoachs!=$coachs) {
                $this->coachm->where('id_member', $member->id)->delete();
                foreach ($coachs as $coach) {
                    $dataCoach = [
                        'id_member' => $member->id,
                        'id_team' => intval($coach),
                    ];

                    $this->coachm->insert($dataCoach);
                }
            }

            //Gestion des joueurs
            //Récupération des joueurs actuels
            $currentPlayers = array_column($this->playerm->getPlayersByIdMember($id),'id_team');

            if(empty($players) || $currentPlayers!=$players) {
                $this->playerm->where('id_member', $member->id)->delete();
                foreach ($players as $player) {
                    $dataPlayer = [
                        'id_member' => $member->id,
                        'id_team' => intval($player),
                    ];

                    $this->playerm->insert($dataPlayer);
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

    public function searchMember(){
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
        $result = $this->mm->quickSearchForSelect2($search, $page, $limit, 'last_name', 'ASC');

        //Réponse JSON
        return $this->response->setJSON($result);
    }
}
