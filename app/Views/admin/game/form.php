<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <?= form_open('admin/game/save'. (isset($game) ? '/'.$game->id : '')) ?>
            <div class="card">
                <div class="card-header text-center">
                    <span class="card-title h3">Création d'un match</span>
                </div>
                <div class="card-body">
                    <!-- START : CHOIX DE L'HORAIRE ET DU GYMNASE -->
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6 hstack mb-3">
                            <label class="form-label mx-3" for="schedule">Horaire<span class="text-danger">*</span></label>
                            <input class="form-control" type="datetime-local" name="schedule" id="schedule" required value="<?= esc($game->schedule ?? '') ?>">
                        </div>
                        <div class="col-md-6 hstack mb-3">
                            <label class="form-label mx-3" for="select-gym">Gymnase<span class="text-danger">*</span></label>
                            <select class="form-select" name="id_gym" id="select-gym" required>
                                <?php if(isset($game->id_gym)): ?>
                                <option value="<?=$game->id_gym?>" selected><?=$game->gym_name?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <!-- END : CHOIX DE L'HORAIRE ET DU GYMNASE -->
                    <!-- START : CATÉGORIE, CHAMPIONNAT ET INFOS FBI -->
                    <div class="row d-flex">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label mx-3" for="id_category">Catégorie<span class="text-danger">*</span></label>
                                    <div class="input-group mx-0">
                                        <select class="form-select" name="id_category" id="select-category" required>
                                            <?php if(isset($game->id_category)): ?>
                                                <option value="<?=$game->id_category?>" selected><?=$game->category?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label mx-3" for="id_division">Championnat</label>
                                    <div class="input-group mx-0">
                                        <select class="form-select" name="id_division" id="select-division">
                                            <?php if(isset($game->id_division)): ?>
                                                <option value="<?=$game->id_division?>" selected><?=$game->division?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label mx-3" for="fbi_number">Numéro FBI</label>
                                    <input class="form-control" type="text" name="fbi_number" id="fbi_number" value="<?= esc($game->fbi_number ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label mx-3" for="e_marque_code">Code E-Marque</label>
                                    <input class="form-control" type="text" name="e_marque_code" id="e_marque_code" value="<?= esc($game->e_marque_code ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END : CATÉGORIE, CHAMPIONNAT ET INFOS FBI -->
                    <!-- START : CHOIX DES ÉQUIPES ET SCORE -->
                    <div class="row">
                        <!-- START : ÉQUIPE À DOMICILE -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body" >
                                    <div class="row mb-3 text-center">
                                        <div class="col">
                                            <span class="card-title h5">Équipe à domicile <span class="text-danger">*</span></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="home_club">Club de l'équipe à domicile</label>
                                                    <div class="input-group">
                                                        <select class="form-select" name="home_club" id="select-home-club" required>
                                                            <?php if(isset($game->home_club)): ?>
                                                                <option value="<?=$game->home_club?>" selected><?=$game->home_club_name?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="zone-home-team">
                                                <?php if(isset($game->home_team)): ?>
                                                    <div class="row mb-3">
                                                        <div class="col">
                                                            <label class="form-label" for="">Équipe de <?= $game->home_club_name ?></label>
                                                            <select class="form-control" name="home_team" id="select-home-team" required>

                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div id="zone-home-team-score">
                                                <?php if(isset($game->home_team)): ?>
                                                    <div class="row d-flex align-items-center">
                                                        <div class="col-6 text-end">
                                                            <label class="form-label me-3" for="home-score-input">Score de <?= $game->home_club_name ?></label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input class="form-control fw-bold fs-4 score-input" type="number" name="home_score" id="home-score-input" value="<?= esc
                                                            ($game->score_home) ?? '' ?>">
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : ÉQUIPE À DOMICILE -->
                        <!-- START : ÉQUIPE À L'EXTÉRIEUR -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row mb-3 text-center">
                                        <div class="col">
                                            <span class="card-title h5">Équipe à l'extérieur <span class="text-danger">*</span></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="away_club">Club de l'équipe à l'extérieur</label>
                                                    <div class="input-group">
                                                        <select class="form-select" name="away_club" id="select-away-club" required>
                                                            <?php if(isset($game->away_club)): ?>
                                                                <option value="<?=$game->away_club?>" selected><?=$game->away_club_name?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="zone-away-team">
                                                <?php if(isset($game->away_team)): ?>
                                                    <div class="row mb-3">
                                                        <div class="col">
                                                            <label class="form-label" for="">Équipe de <?= $game->away_club_name ?></label>
                                                            <select class="form-control" name="away_team" id="select-away-team" required>

                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div id="zone-away-team-score">
                                                <?php if(isset($game->away_team)): ?>
                                                    <div class="row d-flex align-items-center">
                                                        <div class="col-6 text-end">
                                                            <label class="form-label me-3" for="away-score-input">Score de <?= $game->away_club_name ?></label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input class="form-control fw-bold fs-4 score-input" type="number" name="away_score" id="away-score-input" value="<?= esc
                                                            ($game->score_away) ?? ''
                                                            ?>">
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : ÉQUIPE À L'EXTÉRIEUR -->
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <!--START : MVP -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col text-center">
                                                    <span class="card-title fw-bold h5">MVP</span>
                                                </div>
                                                <div class="col-auto ms-auto">
                                                    <span class="btn btn-sm btn-primary ms-auto" id="btn-add-mvp"><i class="fas fa-star"></i> Choisir le MVP </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" id="zone-mvp">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--END : MVP -->
                            <!--START : FAUTES TECHNIQUES -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col text-center">
                                                    <span class="card-title fw-bold h5">Fautes techniques</span>
                                                </div>
                                                <div class="col-auto ms-auto">
                                                    <span class="btn btn-sm btn-primary ms-auto" id="btn-add-technical-foul"><i class="fas fa-plus"></i> Ajouter une faute technique </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" id="zone-technical-foul">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--END : FAUTES TECHNIQUES -->
                        </div>
                        <!--START: SERVICES -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col text-center">
                                            <span class="card-title fw-bold h5">Services</span>
                                        </div>
                                        <div class="col-auto ms-auto">
                                            <span class="btn btn-sm btn-primary ms-auto" id="btn-add-service"><i class="fas fa-plus"></i> Ajouter un service</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" id="zone-services">
                                    <?php if (isset($game) && !empty($game->services)):
                                        $nbServices = 0;
                                        foreach ($game->services as $service):
                                            $nbServices++;?>
                                            <div class="row mb-3 row-service">
                                                <div class="col-11">
                                                    <div class="row mb-2">
                                                        <div class="col-6">
                                                            <select class="form-select" name="services[<?= $nbServices ?>][id_service]" id="service_type_<?= $nbServices ?>">
                                                                <option value="<?= $service['id_service'] ?>"><?= $service['service_label'] ?> </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <select class="form-select" name="services[<?= $nbServices ?>][id_member]" id="service_member_<?= $nbServices ?>">

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <input class="form-control" type="text" name="services[<?= $nbServices ?>][details]" id="service_details_<?= $nbServices ?>"
                                                                   placeholder="Précisions (facultatif)" value="<?= esc($service['details'] ?? '') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-trash-alt text-danger btn-delete-service fs-2"
                                                        data-id-service="<?= esc($service['id_service']) ?>"
                                                        data-id-member="<?= esc($service['id_member']) ?>"
                                                        data-details="<?= esc($service['details'] ?? '') ?>"></i>
                                                </div>
                                            </div>
                                    <?php endforeach;
                                    endif;?>
                                </div>
                            </div>
                        </div>
                        <!--END: SERVICES -->
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary mx-2"><i class="fas fa-save"></i> Valider</button>
                </div>
            </div>
            <?= form_close();?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        let homeClubId = <?= json_encode($game->home_club ?? '')?>;
        let awayClubId = <?= json_encode($game->away_club ?? '')?>;
        let homeTeamId = <?= json_encode($game->home_team ?? '')?>;
        let homeTeamName = <?= json_encode($game->home_team_name ??'')?>;
        let awayTeamId = <?= json_encode($game->away_team ??'')?>;
        let awayTeamName = <?= json_encode($game->away_team_name ??'')?>;
        let TasdonTeam ;
        let deletedServices  = 0;

        //Initialisation Select Gym
        initAjaxSelect2('#select-gym', {url:'/admin/gym/search',searchFields:'name',additionalFields:['fbi_code','club_name'],placeholder:'Rechercher un gymnase'});

        //Initialisation Select Category
        initAjaxSelect2('#select-category', {url:'/admin/category/search',searchFields:'name',placeholder:'Rechercher une catégorie'});

        //Initialisation Select Division
        initAjaxSelect2('#select-division', {url:'/admin/division/search',searchFields:'name',additionalFields:['season_name'],placeholder:'Rechercher un championnat'});

        //GESTION DE L'ÉQUIPE À DOMICILE

        //Initialisation du select des clubs
        initAjaxSelect2('#select-home-club', {url:'/admin/club/search',searchFields:'name',additionalFields:['code'],placeholder:'Rechercher un club'});

        //Initialisation du select2 de l'équipe à domicile si mode édition
        initAjaxSelect2(`#select-home-team`, {url:'/admin/team/search', searchFields:'name',placeholder:'Rechercher un équipe',extraParams: {id_club:homeClubId}});
        //chargement de l'option déjà existante
        if(homeTeamId) {
            let option = new Option(homeTeamName,homeTeamId,true,true);
            $('#select-home-team').append(option);
        }

        //On commence par la sélection du club
        $('#select-home-club').on('change', function(){
            let selectedClub = $(this).select2('data');
            let selectedClubId = selectedClub[0].id;


            //création et apparition d'un select pour choisir l'équipe du club sélectionné
            let row = `
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="">Équipe de ${selectedClub[0]['text']} </label>
                        <select class="form-control" name="home_team" id="select-home-team" required></select>
                    </div>
                </div>
            `;
            $('#zone-home-team').empty().append(row);

            //Initialisation du nouveau select2
            initAjaxSelect2(`#select-home-team`, {url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher un équipe',extraParams: {id_club:selectedClubId}})
        })

       //Une fois l'équipe choisie, apparition de l'input pour le score
        $(document).on('change','#select-home-team', function(){
            let selectedClub = $('#select-home-club').select2('data');
            if (selectedClub[0]['id'] == 1){
                let selectedTeam = $(this).select2('data');
                TasdonTeam = selectedTeam[0]['id'];
            }
            //création et apparition d'un input pour le score
            let row = `
                <div class="row d-flex align-items-center">
                    <div class="col-6 text-end">
                        <label class="form-label me-3" for="home-score-input">Score de ${selectedClub[0]['text']}</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control fw-bold fs-4 score-input" type="number" name="home_score" id="home-score-input">
                    </div>
                </div>
            `;
            $('#zone-home-team-score').empty().append(row);
        })

        //GESTION DE L'ÉQUIPE À L'EXTÉRIEUR

        //Initialisation du select des clubs
        initAjaxSelect2('#select-away-club', {url:'/admin/club/search',searchFields:'name',additionalFields:['code'],placeholder:'Rechercher un club'});

        //Initialisation du select2 de l'équipe à l'extérieur
        initAjaxSelect2(`#select-away-team`, {url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher une équipe',extraParams: {id_club:awayClubId}})
        //chargement de l'option déjà existante
        if(awayTeamId) {
            let option = new Option(awayTeamName,awayTeamId,true,true);
            $('#select-away-team').append(option);
        }

        //On commence par la sélection du club
        $('#select-away-club').on('change', function(){
            let selectedClub = $(this).select2('data');
            let selectedClubId = selectedClub[0].id;

            //création et apparition d'un select pour choisir l'équipe du club sélectionné
            let row = `
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="">Équipe de ${selectedClub[0]['text']} </label>
                        <select class="form-control" name="away_team" id="select-away-team" required></select>
                    </div>
                </div>
            `;
            $('#zone-away-team').empty().append(row);

            //Initialisation du nouveau select2
            initAjaxSelect2(`#select-away-team`, {url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher un équipe',extraParams: {id_club:selectedClubId}});
        })

        //Une fois l'équipe choisie, apparition de l'input pour le score
        $(document).on('change','#select-away-team', function(){
            let selectedClub = $('#select-away-club').select2('data');
            if (selectedClub[0]['id'] == 1){
                let selectedTeam = $(this).select2('data');
                TasdonTeam = selectedTeam[0]['id'];
            }

            //création et apparition d'un input pour le score
            let row = `
                <div class="row d-flex align-items-center">
                    <div class="col-6 text-end">
                        <label class="form-label me-3" for="away-score-input">Score de ${selectedClub[0]['text']}</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control fw-bold fs-4 score-input" type="number" name="away_score" id="away-score-input">
                    </div>
                </div>
            `;
            $('#zone-away-team-score').empty().append(row);
        })

        //GESTION DU CHOIX DU MVP
        $('#btn-add-mvp').on('click', function(){
            let row=`
                <div class="row mb-3">
                    <div class="col">
                        <select class="form-select" name="mvp" id="select-mvp">

                        </select>
                    </div>
                </div>

            `;

            $('#zone-mvp').append(row);

            //Initialisation du select2 du mvp
            initAjaxSelect2(`#select-mvp`, {url:'/admin/player/search', searchFields: 'first_name, last_name', placeholder:'Choisir le joueur', extraParams:{id_team:TasdonTeam}});

        });

        //GESTION DES SERVICES
        let nbServices = $('#zone-services .row-service').length;
        let services = <?= json_encode($services) ?>;
        let gameServices = <?= json_encode($game->services) ?>;

        if(homeClubId == 1) {
            TasdonTeam = homeTeamId;
        } else if(awayClubId == 1) {
            TasdonTeam = awayTeamId;
        }

        //Boucle pour initialiser les select2 sur les services déjà existants
        for (i=1 ; i<=nbServices; i++) {
            //Initialisation du select2 du membre qui rend ce service (en mode édition)
            initAjaxSelect2(`#service_member_${i}`, {url:'/admin/player/search', searchFields: 'first_name, last_name', placeholder:'Rechercher un joueur', extraParams:{id_team:TasdonTeam}});

            let option = new Option((gameServices[i-1]['member_first_name']+' '+gameServices[i-1]['member_last_name']),gameServices[i-1]['id_member'],true,true);
            $(`#service_member_${i}`).append(option);
        }


        //Apparition de la row d'ajout de service au clic sur le bouton d'ajout
        $('#btn-add-service').on('click', function(){
            nbServices++;
            let row=`
                <div class="row mb-3 row-service">
                    <div class="col-11">
                        <div class="row mb-2">
                            <div class="col-6">
                                <select class="form-select" name="services[${nbServices}][id_service]" id="service_type_${nbServices}">

                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="services[${nbServices}][id_member]" id="service_member_${nbServices}">

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input class="form-control" type="text" name="services[${nbServices}][details]" id="service_details_${nbServices}" placeholder="Précisions (facultatif)">
                            </div>
                        </div>
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-center">
                        <i class="fas fa-trash-alt text-danger btn-delete-service fs-2"></i>
                    </div>
                </div>
            `;

            $('#zone-services').append(row);

            //Gestion du select de type de service
            let selectServiceType = $('#service_type_'+nbServices);
            selectServiceType.html(services.map(service=>{return `<option class="form-control" value="${service.id}">${service.label}</option>`}).join(""));

            //Initialisation du select2 du membre qui rend ce service
            initAjaxSelect2(`#service_member_${nbServices}`, {url:'/admin/player/search', searchFields: 'first_name, last_name', placeholder:'Rechercher un joueur', extraParams:{id_team:TasdonTeam}});
        })

        //Gestion de la suppression d'un service
        $(document).on('click', '.btn-delete-service', function(){
            nbServices--;
            let idService = $(this).data('id-service');
            let idMember = $(this).data('id-member');
            $(this).closest('.row-service').remove();
            let deleteInputs = `
                <input type="hidden" name="deletedServices[${deletedServices}][id_service]" value="${idService}">
                <input type="hidden" name="deletedServices[${deletedServices}][id_member]" value="${idMember}">
            `;
            $('#zone-services').append(deleteInputs);
            deletedServices++;
        })
    })

</script>
<style>
    .score-input {
        height: 50px ;
    }

    .btn-delete-service:hover {
        scale:1.20;
        cursor: pointer;
    }
</style>
<?php $this->endSection(); ?>
