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
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="last_name">Nom <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="last_name" id="last_name" value="<?= esc($member->last_name ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="first_name">Prénom <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="first_name" id="first_name" value="<?= esc($member->first_name ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="date_of_birth">Date de naissance <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="date_of_birth" id="date_of_birth" value="<?= esc($member?->date_of_birth?->toDateString() ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="mb-3">Rôle(s) <span class="text-danger">*</span></label>
                            <div class="row row-cols-auto">
                                <?php foreach($roles as $role) : ?>
                                    <div class="col mb-3 role">
                                       <div class="form-check">
                                           <input class="form-check-input" type="checkbox" value="<?=$role['id']?>" id="role-<?=$role['id']?>" name="roles[]" <?= (isset($member->roles) && in_array($role['id'], $member->roles)) ? 'checked' : '' ;?>>
                                           <label class="form-check-label" for="role-<?=$role['id']?>"><?= $role['name'] ?></label>
                                       </div>
                                    </div>

                                <?php endforeach; ?>
                            </div>
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
                        <div class="col-md-6 d-inline-flex align-items-center mb-3">
                            <label class="form-label m-2">Statut de la licence</label>
                            <div class="form-check form-switch mx-2">
                                <input class="form-check-input form-switch" type="checkbox" role="switch" name="license_status" id="license_status" <?= isset($member->license_status) &&
                                $member->license_status == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label mx-2" for="license_status" id="license_status_label">
                                    <?= isset($member->license_status) && $member->license_status == 1 ? 'Validée' : 'Non-validée' ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 d-inline-flex align-items-center mb-3">
                            <label class="form-label text-nowrap mx-2" for="license_code">Code licence</label>
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
                    <div class="row">
                        <div class="col-md-6 d-inline-flex align-items-center mb-3">
                            <label class="form-label text-nowrap mx-2" for="overqualified">Surclassement</label>
                            <select class="form-select" name="overqualified" id="overqualified">
                                <option value="0" <?= isset($member->overqualified) && $member->overqualified == 0 ? 'selected' : '' ?>>Aucun</option>
                                <option value="1" <?= isset($member->overqualified) && $member->overqualified == 1 ? 'selected' : '' ?>>Simple</option>
                                <option value="2" <?= isset($member->overqualified) && $member->overqualified == 2 ? 'selected' : '' ?>>Double</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-inline-flex align-items-center mb-3">
                            <label class="form-label text-nowrap mx-2" for="license_number">Numéro de licence</label>
                            <input class="form-control" type="text" name="license_number" id="license_number" value="<?= esc($member->license_number ?? '') ?>">
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
                            <div class="card-body" id="zone-player">
                                <div class="row mb-3">
                                    <div class="col p-3">
                                        <div class="input-group">
                                            <select class="form-select select-player" id="select-player">
                                            </select>
                                            <span class="input-group-text btn btn-sm btn-primary d-flex align-items-center" id="add-player"><i class="fas fa-plus"></i> Ajouter</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 overflow-auto">
                                    <div class="col" id="zone-player-list">
                                        <?php if(isset($member->player_teams)){
                                            $cpt_teams = 0 ;
                                            foreach ($member->player_teams as $team) :
                                                $cpt_teams ++ ?>
                                                <div class="row row-player">
                                                    <div class="col">
                                                        <div class="card card-player">
                                                            <div class="card-body p-1 d-flex align-items-center">
                                                                <div class="row">
                                                                    <div class="col-auto">
                                                                        <span class="fs-4" id="delete-player-<?= $cpt_teams ?>"><i class="fas fa-trash-alt text-danger delete-player-button"></i></span>
                                                                    </div>
                                                                    <div class="col d-flex align-items-center">
                                                                        <span class="fw-semibold"><?= $team['team_name'] ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="players[]" value="<?= $team['id_team'] ?>">
                                                </div>
                                            <?php endforeach;
                                        } ?>
                                    </div>
                                </div>
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
                            <div class="card-body" id="zone-coach">
                                <div class="row mb-3">
                                    <div class="col p-3">
                                        <div class="input-group">
                                            <select class="form-select select-coach" id="select-coach">
                                            </select>
                                            <span class="input-group-text btn btn-sm btn-primary d-flex align-items-center" id="add-coach"><i class="fas fa-plus"></i> Ajouter</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 overflow-auto">
                                    <div class="col" id="zone-coach-list">
                                        <?php if(isset($member->coach_teams)){
                                            $cpt_teams = 0 ;
                                            foreach ($member->coach_teams as $team) :
                                                $cpt_teams ++ ?>
                                                <div class="row row-coach">
                                                    <div class="col">
                                                        <div class="card card-coach">
                                                            <div class="card-body p-1 d-flex align-items-center">
                                                                <div class="row">
                                                                    <div class="col-auto">
                                                                        <span class="fs-4" id="delete-coach-<?= $cpt_teams ?>"><i class="fas fa-trash-alt text-danger delete-coach-button"></i></span>
                                                                    </div>
                                                                    <div class="col d-flex align-items-center">
                                                                        <span class="fw-semibold"><?= $team['team_name'] ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="coachs[]" value="<?= $team['id_team'] ?>">
                                                </div>
                                            <?php endforeach;
                                        } ?>
                                    </div>
                                </div>
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
                            <input class="form-control" type="number" name="balance" id="balance" min="0" step="0.5" value="<?= $member->balance ?? 0 ?>">
                        </div>
                        <div class="col-md-auto ms-2">
                            <label class="form-label ms-2">Disponibilité</label>
                            <div class="form-check form-switch m-2">
                                <input class="form-check-input form-switch" type="checkbox" role="switch" name="available" id="available" <?= isset($member->available) && $member->available == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label mx-2" for="available"><?= isset($member->available) && $member->available == 1 ? 'Disponible' : 'Indisponible' ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="availability_details">Détails de l'indisponibilité</label>
                        <textarea class="form-control" name="availability_details" id="availability_details" rows="3"><?= $member->details ?? '' ?></textarea>
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
$(document).ready(function () {
    let nbCoachs = $('#zone-coach .card-coach').length ;
    let nbPlayers = $('#zone-player .card-player').length ;
    console.log(nbCoachs, nbPlayers);

    //initialisation select-coach
    initAjaxSelect2(`#select-coach`, {url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher un équipe'});

    //initialisation select-player
    initAjaxSelect2(`#select-player`, {url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher un équipe'});

    //Gestion du clic sur le switch pour le statut de la licence
    $('#license_status').on('change', function(){
        if($(this).is(':checked')){
            $('#license_status_label').text('Validée');
        } else {
            $('#license_status_label').text('Non-validée');
        }
    });

    //Gestion de l'ajout d'une équipe (coach)
    $('#add-coach').on('click', function(){
        let selectedTeam = $('#select-coach').select2('data');
        console.log(selectedTeam);

        // si aucune équipe n'est sélectionnée lors du clic, on bloque la création de la row
        if (!selectedTeam.length) {
            return;
        }

        //Si une équipe est sélectionnée
        nbCoachs++;
        let team=selectedTeam[0] ;
        let row=`
            <div class="row row-coach">
                <div class="col">
                    <div class="card card-coach">
                        <div class="card-body p-1 d-flex align-items-center">
                            <div class="row">
                               <div class="col-auto">
                                   <span class="fs-4" id="delete-coach-${nbCoachs}"><i class="fas fa-trash-alt text-danger delete-coach-button"></i></span>
                               </div>
                                <div class="col d-flex align-items-center">
                                    <span class="fw-semibold" >${team.text}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="coachs[]" value="${team.id}">
            </div>
        `;
        $('#zone-coach-list').prepend(row);
        $('#select-coach').empty();
    });

    // Gestion suppression coach
    $('#zone-coach').on('click' , '.delete-coach-button', function(){
        nbCoachs --;
        $(this).closest('.row-coach').remove();
    })

    //Gestion de l'ajout d'une équipe (joueur)
    $('#add-player').on('click', function(){
        let selectedTeam = $('#select-player').select2('data');
        console.log(selectedTeam);

        // si aucune équipe n'est sélectionnée lors du clic, on bloque la création de la row
        if (!selectedTeam.length) {
            return;
        }

        //Si une équipe est sélectionnée
        nbPlayers++;
        let team=selectedTeam[0] ;
        let row=`
            <div class="row row-player">
                <div class="col">
                    <div class="card card-player">
                        <div class="card-body p-1 d-flex align-items-center">
                            <div class="row">
                               <div class="col-auto">
                                   <span class="fs-4" id="delete-player-${nbPlayers}"><i class="fas fa-trash-alt text-danger delete-player-button"></i></span>
                               </div>
                                <div class="col d-flex align-items-center">
                                    <span class="fw-semibold" >${team.text}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="players[]" value="${team.id}">
            </div>
        `;
        $('#zone-player-list').prepend(row);
        $('#select-player').empty();
    });

    // Gestion suppression joueur
    $('#zone-player').on('click' , '.delete-player-button', function(){
        nbPlayers --;
        $(this).closest('.row-player').remove();
    })
})
</script>
<style>
    #zone-player,#zone-coach-list {
        max-height: 250px;
    }

    .row-coach {
        margin-bottom: 1rem;
    }

    .delete-coach-button:hover,.delete-player-button:hover {
        scale:1.20;
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>
