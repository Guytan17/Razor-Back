<?php
$routes->group('admin', ['namespace' => 'App\Controllers\Admin','filter' => 'group:user,admin'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    //Routes pour la gestion des membres
    $routes->group('member',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Member::index');
        $routes->get('form', 'Member::form'); // accès formulaire de création
        $routes->get('form/(:num)', 'Member::form/$1'); //accès formulaire d'édition
        $routes->post('save', 'Member::saveMember'); // sauvegarde création
        $routes->post('save/(:num)', 'Member::saveMember/$1'); // sauvegarde édition
        $routes->post('switch-active/(:num)', 'Member::switchActiveMember/$1'); //(dés)activation membres
        $routes->get('search', 'Member::searchMember');
    });

    //Routes pour la gestion des clubs
    $routes->group('club',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Club::index');
        $routes->get('form', 'Club::form'); //accès au formulaire de création
        $routes->get('form/(:num)', 'Club::form/$1'); // accès au formulaire d'édition
        $routes->post('save', 'Club::saveClub'); // sauvegarde création
        $routes->post('save/(:num)', 'Club::saveClub/$1'); //sauvegarde édition
        $routes->post('switch-active/(:num)', 'Club::switchActiveClub/$1');
    });

    //Routes pour la gestion des équipes
    $routes->group('team',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Team::index');
        $routes->get('form', 'Team::form'); //accès au formulaire de création
        $routes->get('form/(:num)', 'Team::form/$1'); // accès au formulaire d'édition
        $routes->post('save', 'Team::saveTeam'); // sauvegarde création
        $routes->post('save/(:num)', 'Team::saveTeam/$1'); //sauvegarde édition
        $routes->post('switch-active/(:num)', 'Team::switchActiveTeam/$1');
        $routes->get('search', 'Team::searchTeam');
    });

    //Routes pour la gestion des codes licences
    $routes->group('license-code', ['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'LicenseCode::index');
        $routes->post('insert', 'LicenseCode::insertLicenseCode'); // Sauvegarde création
        $routes->post('update/(:num)', 'LicenseCode::updateLicenseCode/$1');//Sauvegarde édition
        $routes->post('delete/(:num)', 'LicenseCode::deleteLicenseCode/$1');//Suppression d'un rôle
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
        $routes->post('delete/(:num)', 'Season::deleteSeason/$1'); //Suppression d'une saison
    });

    //Routes pour la gestion des championnats
    $routes->group('division',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Division::index');
        $routes->post('insert', 'Division::insertDivision'); //Sauvegarde création
        $routes->post('update/(:num)', 'Division::updateLeague/$1'); //Sauvegarde édition
        $routes->post('switch-active/(:num)', 'Division::switchActiveDivision/$1');//Activation/désactivation d'un championnat
    });

    //Routes pour la gestion des services
    $routes->group('service',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Service::index');
        $routes->post('insert', 'Service::insertService'); // sauvegarde création
        $routes->post('update/(:num)', 'Service::updateService/$1');//sauvegarde édition
        $routes->post('delete/(:num)', 'Service::deleteService/$1'); //suppression
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