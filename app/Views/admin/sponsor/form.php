<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

    <div class="container-fluid">
        <!-- START : ZONE POUR LES ALERTES BOOTSTRAP -->
        <div class="row mb-3">
            <div class="col-12">
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- END : ZONE POUR LES ALERTES BOOTSTRAP -->

        <div class="row">
            <div class="col">
                <div class="card">
                    <?= form_open_multipart('admin/sponsor/insert') ?>
                    <div class="card-header">
                        <span class="card-title h5">Création d'un nouveau sponsor</span>
                    </div>
                    <div class="card-body">
                        <!-- IMAGE DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col text-center">
                                <img class="img-thumbnail mb-3" src="<?= esc(base_url('/assets/img/default.png')) ; ?>" title="image du sponsor" alt="image du sponsor " id="logoPreview">
                                <input class="form-control" type="file" name="logo" id="logo">
                            </div>
                        </div>
                        <!-- NOM DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="name">Nom du sponsor <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="name" value="<?=old('name'); ?>" required>
                            </div>
                        </div>
                        <!-- IMPORTANCE DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="rank">Niveau d'importance du sponsor <span class="text-danger">*</span></label>
                                <select class="form-select" name="rank" id="rank" required>
                                    <?php for ($i = 1; $i <= 9; $i++): ?>
                                        <option value="<?= $i ?>" <?=old('rank') === $i ? 'selected' : '';?>>Rang <?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <!-- SPECIFICATIONS DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="specifications">Caractéristiques et instructions</label>
                                <textarea class="form-control" name="specifications" id="specifications" rows="3"><?= old('specifications'); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le sponsor</button>
                    </div>
                    <?= form_close() ; ?>
                </div>
            </div>
        </div>
    </div>

<?php $this->endsection() ; ?>