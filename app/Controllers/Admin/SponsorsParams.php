<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DotationTypeModel;
use App\Models\SponsorRankModel;

class SponsorsParams extends AdminController
{
    protected $sponsorRankModel;
    protected $dotationTypeModel;

    public function __construct(){
        $this->sponsorRankModel = new SponsorRankModel();
        $this->dotationTypeModel = new DotationTypeModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Sponsors - paramètres'
        ];
        $this->addBreadcrumb('Sponsors - paramètres','');
        return $this->render('admin/sponsors-params',$data);
    }
}
