<?php
$routes->group('admin', ['namespace' => 'App\Controllers\Admin','filter' => 'group:user,admin'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    //Routes pour la gestion des membres
    $routes->group('member',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Member::index');
        $routes->get('form', 'Member::form');
    });

    //Routes pour la gestion des roles
    $routes->group('role',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Role::index');
        $routes->post('insert', 'Role::insertRole'); // Sauvegarde création
        $routes->post('update/(:num)', 'Role::updateRole/$1'); //Sauvegarde édition
        $routes->post('delete/(:num)', 'Role::deleteRole/$1'); //Suppression d'un rôle
    });

    // Routes pour la gestion des catégories
    $routes->group('category',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Category::index');
        $routes->post('insert', 'Category::insertCategory'); //Sauvegarde création
        $routes->post('update/(:num)', 'Category::updateCategory/$1'); //Sauvegarde édition
        $routes->post('delete/(:num)', 'Category::deleteCategory/$1'); //suppression d'une catégorie
    });

    //Routes pour la gestion des saisons
    $routes->group('season',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Season::index');
        $routes->post('insert', 'Season::insertSeason'); //Sauvegarde création
        $routes->post('update/(:num)', 'Season::updateSeason/$1');//Sauvegarde édition
    });

    //Routes pour la gestion des championnats
    $routes->group('league',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'League::index');
        $routes->post('insert', 'League::insertLeague'); //Sauvegarde création
        $routes->post('update/(:num)', 'League::updateLeague/$1'); //Sauvegarde édition
    });

    // Routes pour la gestion des utilisateurs (admin uniquement)
    $routes->group('users', ['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('form', 'Users::form');              // Création
        $routes->get('form/(:num)', 'Users::form/$1');    // Édition
        $routes->post('save', 'Users::save');             // Sauvegarde création
        $routes->post('save/(:num)', 'Users::save/$1');   // Sauvegarde mise à jour
        $routes->post('toggle-active/(:num)', 'Users::toggleActive/$1');
        $routes->get('delete/(:num)', 'Users::delete/$1');
        $routes->post('delete-avatar/(:num)', 'Users::deleteAvatar/$1');
    });

    // Routes pour les réglages (admin uniquement)
    $routes->group('reglages', ['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Settings::index');
        $routes->post('save', 'Settings::save');
        $routes->post('delete-logo', 'Settings::deleteLogo');
    });
});