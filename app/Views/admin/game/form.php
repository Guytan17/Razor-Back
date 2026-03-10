<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <?= form_open('admin/game/save') ?>
            <div class="card">
                <div class="card-header text-center">
                    <span class="card-title h3">Création d'un match</span>
                </div>
                <div class="card-body">
                    <!-- START : CHOIX DE L'HORAIRE ET DU GYMNASE -->
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6 hstack mb-3">
                            <label class="form-label mx-3" for="schedule">Horaire<span class="text-danger">*</span></label>
                            <input class="form-control" type="datetime-local" name="schedule" id="schedule" required>
                        </div>
                        <div class="col-md-6 hstack mb-3">
                            <label class="form-label mx-3" for="select-gym">Gymnase<span class="text-danger">*</span></label>
                            <select class="form-select" name="id_gym" id="select-gym" required>

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

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label mx-3" for="id_division">Championnat</label>
                                    <div class="input-group mx-0">
                                        <select class="form-select" name="id_division" id="select-division">

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label mx-3" for="fbi_number">Numéro FBI</label>
                                    <input class="form-control" type="text" name="fbi_number" id="fbi_number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label mx-3" for="e_marque_code">Code E-Marque</label>
                                    <input class="form-control" type="text" name="e_marque_code" id="e_marque_code">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END : CATÉGORIE, CHAMPIONNAT ET INFOS FBI -->
                    <!-- START : CHOIX DES ÉQUIPES ET SCORE -->
                    <div class="row">
                        <!-- START : ÉQUIPE À DOMICILE -->
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body" id="zone-home-team">
                                    <div class="row mb-3 text-center">
                                        <div class="col">
                                            <span class="card-title h5">Équipe à domicile <span class="text-danger">*</span></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="home_club">Club de l'équipe à domicile</label>
                                            <div class="input-group">
                                                <select class="form-select" name="home_club" id="select-home-club" required>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : ÉQUIPE À DOMICILE -->
                        <!-- START : ÉQUIPE À L'EXTÉRIEUR -->
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body" id="zone-away-team">
                                    <div class="row mb-3 text-center">
                                        <div class="col">
                                            <span class="card-title h5">Équipe à l'extérieur <span class="text-danger">*</span></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="away_club">Club de l'équipe à l'extérieur</label>
                                            <div class="input-group">
                                                <select class="form-select" name="away_club" id="select-away-club" required>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : ÉQUIPE À L'EXTÉRIEUR -->
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-sm btn-primary mx-2"><i class="fas fa-save"></i> Valider</button>
                </div>
            </div>
            <?= form_close();?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

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
            console.log(selectedClubId);


            //création et apparition d'un select pour choisir l'équipe du club sélectionné
            let row = `
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="">Équipe de ${selectedClub[0]['text']} </label>
                        <select class="form-control" name="home_team" id="select-home-team" required></select>
                    </div>
                </div>
            `;
            $('#zone-home-team').append(row);

            //Initialisation du nouveau select2
            initAjaxSelect2(`#select-home-team`, {url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher un équipe',extraParams: {id_club:selectedClubId}})
        })

        //GESTION DE L'ÉQUIPE À L'EXTÉRIEUR

        //Initialisation du select des clubs
        initAjaxSelect2('#select-away-club', {url:'/admin/club/search',searchFields:'name',additionalFields:['code'],placeholder:'Rechercher un club'});

        //On commence par la sélection du club
        $('#select-away-club').on('change', function(){
            let selectedClub = $(this).select2('data');
            let selectedClubId = selectedClub[0].id;
            console.log(selectedClubId);

            //création et apparition d'un select pour choisir l'équipe du club sélectionné
            let row = `
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="">Équipe de ${selectedClub[0]['text']} </label>
                        <select class="form-control" name="away_team" id="select-away-team" required></select>
                    </div>
                </div>
            `;
            $('#zone-away-team').append(row);

            //Initialisation du nouveau select2
            initAjaxSelect2(`#select-away-team`, {url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher un équipe',extraParams: {id_club:selectedClubId}})
        })

    })

</script>
<style>
</style>
<?php $this->endSection(); ?>
