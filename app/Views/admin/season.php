<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <!-- START : ZONE CREATION -->
            <div class="card">
                <?= form_open('/admin/season/insert') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'une nouvelle saison</span>
                </div>
                <div class="card-body">
                    <div class="row">
                       <div class="col">
                           <label class="form-label" for="name">Nom de la saison <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="name" id="name" value="<?=old('name')?>" required>
                       </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="start_date">Date de début de saison <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="start_date" id="start_date" value="<?=old('start_date')?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="end_date">Date de fin de saison <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="end_date" id="end_date" value="<?=old('start_date')?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la saison</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION -->
        </div>
        <div class="col-md-8">
            index saison
        </div>
    </div>
</div>

<?= $this->endSection() ?>