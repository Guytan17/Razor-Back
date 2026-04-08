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
        $routes->post('import', 'Member::importMember');//Routes pour l'import du CSV
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

    //Routes pour les joueurs
    $routes->group('player',['filter' => 'group:admin'], function($routes) {
        $routes->get('search', 'Player::searchPlayer');
    });

    //Routes pour les matchs
    $routes->group('game',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Game::index');
        $routes->get('form', 'Game::form');//formulaire de création
        $routes->get('form/(:num)', 'Game::form/$1'); //formulaire d'édition
        $routes->post('save', 'Game::saveGame'); //sauvegarde création
        $routes->post('save/(:num)', 'Game::saveGame/$1'); //sauvegarde édition
        $routes->post('switch-active/(:num)', 'Game::switchActiveGame/$1'); //activation/désactivation
        $routes->get('search', 'Game::searchGame');
        $routes->post('import', 'Game::importGames');
    });

    //Routes pour la gestion des championnats
    $routes->group('division',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Division::index');
        $routes->post('insert', 'Division::insertDivision'); //Sauvegarde création
        $routes->post('update/(:num)', 'Division::updateDivision/$1'); //Sauvegarde édition
        $routes->post('switch-active/(:num)', 'Division::switchActiveDivision/$1');//Activation/désactivation d'un championnat
        $routes->get('search', 'Division::searchDivision');
    });

    //Routes pour la gestion des clubs
    $routes->group('club',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Club::index');
        $routes->get('form', 'Club::form'); //accès au formulaire de création
        $routes->get('form/(:num)', 'Club::form/$1'); // accès au formulaire d'édition
        $routes->post('save', 'Club::saveClub'); // sauvegarde création
        $routes->post('save/(:num)', 'Club::saveClub/$1'); //sauvegarde édition
        $routes->post('switch-active/(:num)', 'Club::switchActiveClub/$1'); //activation/désactivation
        $routes->get('search', 'Club::searchClub');
        $routes->post('import', 'Club::importClubs');
    });

    //Routes pour la gestion des gymnases
    $routes->group('gym',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Gym::index');
        $routes->get('form', 'Gym::form'); //accès au formulaire de création
        $routes->get('form/(:num)', 'Gym::form/$1'); // accès au formulaire d'édition
        $routes->post('save', 'Gym::saveGym'); // sauvegarde création
        $routes->post('save/(:num)', 'Gym::saveGym/$1'); //sauvegarde édition
        $routes->post('delete/(:num)', 'Gym::deleteGym/$1'); //Suppression d'un gymnase
        $routes->get('search', 'Gym::searchGym');
        $routes->post('import', 'Gym::importGyms');
        $routes->get('download-unsaved/(:any)', 'Gym::downloadUnsavedGym/$1');
    });

    //Routes pour les villes
    $routes->group('city',['filter' => 'group:admin'], function($routes) {
        $routes->get('search', 'City::searchCity');
    });

    //Routes pour les sponsors
    $routes->group('sponsor',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Sponsor::index');
        $routes->post('insert', 'Sponsor::insertSponsor'); //sauvegarde création
        $routes->post('update/(:num)', 'Sponsor::updateSponsor/$1'); //sauvegarde édition
        $routes->post('delete/(:num)', 'Sponsor::deleteSponsor/$1'); // suppression sponsor
    });

    //Routes pour les fautes techniques
    $routes->group('technical-foul',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'TechnicalFoul::index');
        $routes->post('insert', 'TechnicalFoul::insertTechnicalFoul');
        $routes->post('update/(:num)', 'TechnicalFoul::updateTechnicalFoul/$1');
        $routes->post('delete/(:num)', 'TechnicalFoul::deleteTechnicalFoul/$1');
    });

    //Routes pour la gestion des codes licences
    $routes->group('license-code', ['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'LicenseCode::index');
        $routes->post('insert', 'LicenseCode::insertLicenseCode'); // Sauvegarde création
        $routes->post('update/(:num)', 'LicenseCode::updateLicenseCode/$1');//Sauvegarde édition
        $routes->post('delete/(:num)', 'LicenseCode::deleteLicenseCode/$1');//Suppression d'un rôle
    });

    //Routes pour la gestion des paramètres des fautes techniques
    $routes->group('technical-foul-params', ['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'TechnicalFoulParams::index');
        $routes->post('insert-type', 'TechnicalFoulParams::insertType'); //Sauvegarde création des Types
        $routes->post('update-type/(:num)', 'TechnicalFoulParams::updateType/$1');//Sauvegarde édition des Types
        $routes->post('delete-type/(:num)', 'TechnicalFoulParams::deleteType/$1'); // Suppression des Types
        $routes->post('insert-classification', 'TechnicalFoulParams::insertClassification'); //Sauvegarde création des Classifications
        $routes->post('update-classification/(:num)', 'TechnicalFoulParams::updateClassification/$1');//Sauvegarde édition des Classifications
        $routes->post('delete-classification/(:num)', 'TechnicalFoulParams::deleteClassification/$1'); // Suppression des Classifications
        $routes->get('search-type', 'TechnicalFoulParams::searchType');
        $routes->get('search-classification', 'TechnicalFoulParams::searchClassification');
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
        $routes->get('search', 'Category::searchCategory');
    });

    //Routes pour la gestion des saisons
    $routes->group('season',['filter' => 'group:admin'], function($routes) {
        $routes->get('/', 'Season::index');
        $routes->post('insert', 'Season::insertSeason'); //Sauvegarde création
        $routes->post('update/(:num)', 'Season::updateSeason/$1');//Sauvegarde édition
        $routes->post('delete/(:num)', 'Season::deleteSeason/$1'); //Suppression d'une saison
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