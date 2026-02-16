<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php echo form_open('/admin/member/save' . (isset($member) && $member ? '/' . $member->id : '')); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <?php if(isset($member->id)) : ?>
                    <span class="card-title h3">Modification de <?= $member->first_name . " " . $member->last_name ?></span>
                    <?php else : ?>
                    <span class="card-title h3">Création d'un membre</span>
                    <?php endif; ?>
                </div>
                <!-- START : ZONE INFOS -->
                <div class="card-body">
                    <!-- START : ZONE AVEC INFOS GÉNÉRALES DU MEMBRE -->
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="last_name">Nom <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="last_name" id="last_name" value="<?= esc($member->last_name ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="first_name">Prénom <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="first_name" id="first_name" value="<?= esc($member->first_name ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="date_of_birth">Date de naissance <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="date_of_birth" id="date_of_birth" value="<?= esc($member?->date_of_birth?->toDateString() ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="role">Rôle <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" id="role" required>
                                <?php foreach($roles as $role): ?>
                                    <option value=<?= esc($role['id']) ?> <?= isset($member) && $role['id'] == $member->id_role ? 'selected' : '' ?>><?= esc($role['name']) ?></option>
                                <?php endforeach ; ?>
                            </select>
                        </div>
                    </div>
                    <!-- END : ZONE AVEC INFOS GÉNÉRALES DU MEMBRE -->
                    <!-- START : ZONE POUR AJOUTER UN CONTACT -->
                    <div class="m-2">
                        <span class="btn btn-sm btn-secondary">
                            <i class="fas fa-plus"></i> Ajouter un contact
                        </span>
                    </div>
                    <div class="row">
                        <div class="">

                        </div>
                    </div>
                    <!-- END : ZONE POUR AJOUTER UN CONTACT -->
                    <!-- START : ZONE CONCERNANT LA LICENCE -->
                    <div class="row">
                        <div class="col-md-6 hstack">
                            <div class="col-auto">
                                <label class="form-label ms-2">Statut de la licence</label>
                                <div class="form-check form-switch m-2">
                                    <input class="form-check-input form-switch" type="checkbox" role="switch" name="license_status" id="license_status">
                                    <label class="form-check-label mx-2" for="license_status"> Non-validée</label>
                                </div>
                            </div>
                            <div class="col mx-2">
                                <label class="form-label" for="license_code">Code licence</label>
                                <select class="form-select" name="license_code" id="license_code" required>
                                    <?php foreach($license_codes as $license_code): ?>
                                        <option value=<?=esc($license_code['id'])?> <?= isset($member) && $license_code['id'] == $member->id_license_code ? 'selected' : '' ?>><?=esc($license_code['code'])
                                            ?> -
                                            <?=
                                            esc($license_code['explanation']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 hstack">
                            <div class="col-auto mx-2">
                                <label class="form-label" for="overqualified">Surclassement</label>
                                <select class="form-select" name="overqualified" id="overqualified">
                                    <option value="0" selected>Aucun</option>
                                    <option value="1">Simple</option>
                                    <option value="2">Double</option>
                                </select>
                            </div>
                            <div class="col m-2">
                                <label class="form-label" for="license_number">Numéro de licence</label>
                                <input class="form-control" type="text" name="license_number" id="license_number" value="<?= esc($member->license_number ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    <!-- END : ZONE CONCERNANT LA LICENCE -->
                </div>
                <!-- END : ZONE INFOS -->
                <!-- START : ZONE ÉQUIPES -->
                <div class="row mx-3">
                    <!-- START : EN TANT QUE JOUEUR -->
                    <div class="col-md-6 mb-3">
                        <div class="card zone-team">
                            <div class="card-header text-center">
                                <span class="card-title"><span class="fw-bold h5">Équipes</span> <span class="fw-semibold h6">(joueur)</span></span>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                    <!-- END : EN TANT QUE JOUEUR -->
                    <!-- START : EN TANT QUE COACH -->
                    <div class="col-md-6">
                        <div class="card zone-team">
                            <div class="card-header text-center">
                                <span class="card-title"><span class="fw-bold h5">Équipes</span> <span class="fw-semibold h6">(coach)</span></span>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                    <!-- END : EN TANT QUE COACH -->
                </div>
                <!-- END : ZONE ÉQUIPES -->
                <!-- START : ZONE INFOS BASKET -->
                <div class="row m-3">
                    <div class="col-md-6 hstack">
                        <div class="col mb-2">
                            <label class="form-label" for="balance">Dette (en €)</label>
                            <input class="form-control" type="number" name="balance" id="balance" min="0" step="0.5">
                        </div>
                        <div class="col-md-auto ms-2">
                            <label class="form-label ms-2">Disponibilité</label>
                            <div class="form-check form-switch m-2">
                                <input class="form-check-input form-switch" type="checkbox" role="switch" name="available" id="available">
                                <label class="form-check-label mx-2" for="available"> Non-disponible</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="availability_details">Détails de l'indisponibilité</label>
                        <textarea class="form-control" name="availability_details" id="availability_details" rows="3"></textarea>
                    </div>
                </div>
                <!-- START : STATS DU JOUEUR -->
                <div class="row m-3">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header text-center">
                                <span class="card-title h5">MVP</span>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-center">
                                <span class="card-title h5">Fautes techniques</span>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- END : STATS DU JOUEUR -->
                <!-- END : ZONE INFOS BASKET -->
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-sm btn-primary mx-2"><i class="fas fa-save"></i> Valider</button>
                </div>
            </div>
        </div>
    </div>

<?php echo form_close() ?>
</div>
<script>

</script>
<style>
    .zone-team {
        min-height: 150px;
    }
</style>
<?= $this->endSection() ?>
