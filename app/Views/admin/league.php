<?php $this->extend('layouts/admin'); ?>

<?php $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open('/admin/league/insert') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un nouveau championnat</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="name">Nom du championnat <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="name" value="<?=old('name')?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="id_season">Saison</label>
                            <select class="form-select" name="id_season" id="id_season">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le rôle</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <div class="col-md-8">
            coucou de l'index des leagues
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
