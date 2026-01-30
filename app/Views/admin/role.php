<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <?= form_open('/admin/role/save') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un nouveau rôle</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="name">Nom du role</label>
                    <input class="form-control" type="text" name="name" id="name">
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le rôle</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <div class="col-md-8">
            coucou de l'index role
        </div>
    </div>
</div>

<?= $this->endSection() ?>
