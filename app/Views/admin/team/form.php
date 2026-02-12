<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<?php echo form_open('admin/team/save'.(isset($team) && $team ? $team->id : '')) ; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <?php if (isset($team)) : ?>
                        <span class="card-title h3">Modification de l'équipe <?= $team->name ?></span>
                    <?php else : ?>
                        <span class="card-title h3">Création d'une équipe</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <!-- START : INFOS DE L'ÉQUIPE -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Nom de l'équipe <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="name" value="<?= $team->name ??'' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="id_club">Club</label>
                            <select class="form-select" name="id_club" id="id_club">
                                <?php foreach ($clubs as $club) : ?>
                                    <option value="<?= $club['id'] ?>"><?= $club['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="id_season">Saison</label>
                            <select class="form-select" name="id_season" id="id_season">
                                <?php foreach ($seasons as $season) : ?>
                                    <option value="<?= $season['id'] ?>"><?= $season['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="id_category">Club</label>
                            <select class="form-select" name="id_category" id="id_category">
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- END : INFOS DE L'ÉQUIPE -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ; ?>