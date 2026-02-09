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

    public function form () {
        $roles = $this->rm->getAllRoles();
        $license_codes = $this->lcm->getAllLicenseCodes();
        $data = [
            'title' => 'Ajout d\'un membre',
            'roles' => $roles,
            'license_codes' => $license_codes,
        ];
        $this->addBreadcrumb('Liste des membres','/admin/member');
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
}
