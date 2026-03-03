<?php $this->extend('/layouts/admin'); ?>

<?php $this->section('content'); ?>


<div class="container-fluid">
    <div class="row">
        <!-- START : ZONE CRÉATION -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open_multipart('admin/sponsor/insert') ?>
                    <div class="card-header">
                        <span class="card-title h5">Création d'un nouveau sponsor</span>
                    </div>
                    <div class="card-body">
                        <!-- IMAGE DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <img class="img-thumbnail mb-3" src="" alt="image de ">
                                <input class="form-control" type="file">
                            </div>
                        </div>
                        <!-- NOM DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="name">Nom du sponsor <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="name" required>
                            </div>
                        </div>
                        <!-- IMPORTANCE DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="rank">Niveau d'importance du sponsor <span class="text-danger">*</span></label>
                                <select class="form-select" name="rank" id="rank" required>
                                    <?php for ($i = 1; $i <= 9; $i++): ?>
                                    <option value="<?= $i ?>">Rang <?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <!-- SPECIFICATIONS DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="specifications">Caractéristiques et instructions</label>
                                <textarea class="form-control" name="specifications" id="specifications" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le sponsor</button>
                </div>
                <?= form_close() ; ?>
            </div>
        </div>
        <!-- END : ZONE CRÉATION -->
        <!-- START : ZONE INDEX -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des sponsors</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="sponsorsTable">
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Logo</th>
                            <th>Nom du sponsor</th>
                            <th>Niveau d'importance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- chargé en Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END : ZONE INDEX -->
    </div>
</div>

<?php $this->endSection();