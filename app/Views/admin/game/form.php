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
                    <div class="row d-flex justify-content-between">
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
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col">
                                            <span class="card-title h5">Équipe à domicile</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col">
                                            <span class="card-title h5">Équipe à l'extérieur</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
