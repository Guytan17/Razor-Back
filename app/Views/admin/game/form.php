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
                                                            <select class="form-control" name="home_team" id="select-away-team" required>
                                                                <option value="<?= $game->home_team ?>"><?= $game->home_team_name ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div id="zone-home-team-score">
                                                <?php if(isset($game->home_team)): ?>
                                                    <div class="row mb-3 d-flex align-items-center">
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
                                                                <option value="<?= $game->away_team ?>"><?= $game->away_team_name ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div id="zone-away-team-score">
                                                <?php if(isset($game->away_team)): ?>
                                                    <div class="row mb-3 d-flex align-items-center">
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
                            <!--END : MVP -->
                            <!--START : FAUTES TECHNIQUES -->
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
        let TasdonTeam ;

        //Initialisation Select Gym
        initAjaxSelect2('#select-gym', {url:'/admin/gym/search',searchFields:'name',additionalFields:['fbi_code','club_name'],placeholder:'Rechercher un gymnase'});

        //Initialisation Select Category
        initAjaxSelect2('#select-category', {url:'/admin/category/search',searchFields:'name',placeholder:'Rechercher une catégorie'});

        //Initialisation Select Division
        initAjaxSelect2('#select-division', {url:'/admin/division/search',searchFields:'name',additionalFields:['season_name'],placeholder:'Rechercher un championnat'});

        //GESTION DE L'ÉQUIPE À DOMICILE

        //Initialisation du select des clubs
        initAjaxSelect2('#select-home-club', {url:'/admin/club/search',searchFields:'name',additionalFields:['code'],placeholder:'Rechercher un club'});

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
                <div class="row mb-3 d-flex align-items-center">
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
                <div class="row mb-3 d-flex align-items-center">
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

        //Gestion de l'ajout de services
        let nbServices = $('#zone-services .row').length;
        let services = <?= json_encode($services) ?>;
        console.log(services);

        //Apparition de la row d'ajout de service au clic sur le bouton d'ajout
        $('#btn-add-service').on('click', function(){
        console.log(TasdonTeam);
            nbServices++;
            let row=`
                <div class="row mb-3">
                    <div class="col-5">
                        <select class="form-select" name="service_type_id" id="service_type_${nbServices}">

                        </select>
                    </div>
                    <div class="col-6">
                        <select class="form-select" name="service_member_id" id="service_member_${nbServices}">

                        </select>
                    </div>
                    <div class="col-1">
                    </div>
                </div>

            `;

            $('#zone-services').append(row);

            //Gestion du select de type de service
            let selectServiceType = $('#service_type_'+nbServices);
            selectServiceType.html(services.map(service=>{return `<option class="form-control" value="${service.id}">${service.label}</option>`}).join(""));

            //Initialisation du select2 du membre qui rend ce service
            initAjaxSelect2(`#service_member_${nbServices}`, {url:'/admin/player/search', searchFields: 'first_name, last_name', placeholder:'Rechercher un membre',
                extraParams:{id_team:TasdonTeam}});

        })
    })

</script>
<style>
    .score-input {

        height: 50px ;
    }
</style>
<?php $this->endSection(); ?>
