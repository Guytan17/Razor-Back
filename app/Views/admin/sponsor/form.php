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
                        <?php if (isset($sponsor) && $sponsor): ?>
                            <span class="card-title h3">Modification de <?=$sponsor['name'] ?></span>
                        <?php else: ?>
                            <span class="card-title h3">Création d'un sponsor</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <!-- IMAGE DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col-md-6 text-center">
                                <img class="img-thumbnail mb-3" src="<?= (isset($sponsor['media_id'])) ? get_media_url( $sponsor['media_id'],'medium', base_url('/assets/img/default.png')) : '/assets/img/default.png'
                                ; ?>" title="image du sponsor" alt="image du sponsor " id="logoPreview">
                                <input class="form-control" type="file" name="logo" id="logo">
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <!-- NOM DU SPONSOR -->
                                    <div class="col">
                                        <label class="form-label" for="name">Nom du sponsor <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" id="name" value="<?=old('name'); ?>" required>
                                    </div>
                                    <!-- IMPORTANCE DU SPONSOR -->
                                        <div class="col">
                                            <label class="form-label" for="rank"> Niveau d'importance <span class="text-danger">*</span></label>
                                            <select class="form-select" name="rank" id="rank" required>
                                                <?php for ($i = 1; $i <= 9; $i++): ?>
                                                    <option value="<?= $i ?>" <?=old('rank') === $i ? 'selected' : '';?>>Rang <?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                </div>
                                <!-- SLOGAN DU SPONSOR -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="slogan">Slogan du sponsor</label>
                                        <input class="form-control" type="text" name="slogan" id="slogan" value="<?=old('slogan'); ?>">
                                    </div>
                                </div>
                                <!-- DOTATION -->
                                <div class="row mb-3">
                                    <!-- TYPE DOTATION -->
                                    <div class="col-6">
                                        <label class="form-label" for="type_dotation">Type de dotation <span class="text-danger">*</span></label>
                                        <select class="form-select" name="type_dotation" id="type_dotation" required>

                                        </select>
                                    </div>
                                    <!-- MONTANT DOTATION -->
                                    <div class="col-6">
                                        <label class="form-label" for="amount_dotation">Montant de dotation <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" name="amount_dotation" id="amount_dotation" min="0" required>
                                            <span class="input-group-text">€</span>
                                        </div>

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

<style>
    #logoPreview {
        max-height: 320px;
        width: auto;
        object-fit: contain;
    }
</style>

<?php $this->endsection() ; ?>