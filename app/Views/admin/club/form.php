<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ?>

<?php echo form_open_multipart('/admin/club/save' . (isset($club) && $club ? "/". $club['id'] : "")); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <span class="card-title h3">Création d'un club</span>
                </div>
                <div class="card-body">
                    <!-- START : INFO GENERALES DU CLUB -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label" for="code">Code FBI du club</label>
                            <input class="form-control" type="text" name="code" id="code" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="name">Nom du club</label>
                            <input class="form-control" type="text" name="name" id="name">
                        </div>
                    </div>
                    <div class="row mb-3">
                       <div class="col-md-6">
                           <label class="form-label" for="color_1">Couleur 1</label>
                           <input class="form-control" type="text" name="color_1" id="color_1">
                       </div>
                        <div class="col-md-6">
                            <label class="form-label" for="color_2">Couleur 2</label>
                            <input class="form-control" type="text" name="color_2" id="color_2">
                        </div>
                    </div>
                    <!-- END : INFO GENERALES DU CLUB -->
                    <div class="row mb-3">
                        <!-- START : EQUIPES -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header text-center">
                                    <span class="card-title fw-bold h5">Équipes du club</span>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                        <!-- END : EQUIPES -->
                        <!-- START : GYMNASES -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header text-center">
                                    <span class="card-title fw-bold h5">Gymnases du club</span>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>

                        </div>
                        <!-- END : GYMNASES -->
                    </div>

                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-sm btn-primary mx-2"><i class="fas fa-save"></i> Valider</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>

<?php $this->endSection() ?>
