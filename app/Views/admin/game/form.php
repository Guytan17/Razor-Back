<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <span class="card-title h3">Création d'un match</span>
                </div>
                <div class="card-body">
                    <!-- START : CHOIX DE L'HORAIRE ET DU GYMNASE -->
                    <div class="row mb-3 d-flex justify-content-center">
                        <div class="col-auto hstack">
                            <label class="form-label mx-3" for="schedule">Horaire</label>
                            <input class="form-control" type="datetime-local" name="schedule" id="schedule">
                        </div>
                        <div class="col-auto hstack">
                            <label class="form-label mx-3" for="id_gym">Gymnase</label>
                            <select class="form-select" name="id_gym" id="id_gym">
                                <option value="1">Gymnase 1</option>
                            </select>
                        </div>
                    </div>
                    <!-- END : CHOIX DE L'HORAIRE ET DU GYMNASE -->
                    <!-- START : CHAMPIONNAT ET INFOS FBI -->
                    <div class="row d-flex">
                        <div class="col-md-4 mb-3">
                            <label class="form-label mx-3" for="id_division">Championnat</label>
                            <select class="form-select" name="id_division" id="id_division">
                                <option value="1">Championnat 1</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <label class="form-label mx-3" for="fbi_number">Numéro FBI</label>
                            <input class="form-control" type="text" name="fbi_number" id="fbi_number">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label mx-3" for="e_marque_code">Code E-Marque</label>
                            <input class="form-control" type="text" name="e_marque_code" id="e_marque_code">
                        </div>
                    </div>
                    <!-- END : CHAMPIONNAT ET INFOS FBI -->
                    <!-- START : CHOIX DES ÉQUIPES ET SCORE -->
                    <div class="row">
                        <!-- START : ÉQUIPE À DOMICILE -->
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body" id="zone-home-team">
                                    <div class="row mb-3 text-center">
                                        <div class="col">
                                            <span class="card-title h5">Équipe à domicile</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="home_club">Club de l'équipe à domicile</label>
                                            <select class="form-select" name="home_club" id="select-home-club">

                                            </select>
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
                                            <span class="card-title h5">Équipe à l'extérieur</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="away_club">Club de l'équipe à l'extérieur</label>
                                            <select class="form-select" name="away_club" id="select-away-club">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : ÉQUIPE À L'EXTÉRIEUR -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

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
                        <select class="form-control" name="home_team" id="select-home-team"></select>
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
                        <select class="form-control" name="away_team" id="select-away-team"></select>
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
