<?php

namespace App\Controllers;

class Home extends SiteController
{
    protected $menu = 'accueil';

    public function index(): string
    {
        return $this->render('/front/home');
    }
}
