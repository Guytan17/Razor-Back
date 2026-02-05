<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LicenseCodeModel;
use CodeIgniter\HTTP\ResponseInterface;

class LicenseCode extends AdminController
{
    protected $lcm;

    public function __construct(){
        $this->lcm = new LicenseCodeModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Codes licences'
        ];
        $this->addBreadcrumb('Codes licences','');
        return $this->render('admin/license-code',$data);
    }

    public function insertLicenseCode() {
        try {
            $dataLicenseCode = [
                'code' => $this->request->getPost('code'),
                'explanation' => $this->request->getPost('explanation'),
            ];
            if ($this->lcm->insert($dataLicenseCode)) {
                $this->success('Code licence créé avec succès');
            } else {
                foreach ($this->lcm->errors() as $error) {
                    $this->error($error);
                }
            }
            return $this->redirect('admin/license-code');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return redirect()->back()->withInput();
        }
    }
}
