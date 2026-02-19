<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<?php echo form_open('admin/team/save'.(isset($team) && $team ? '/' . $team->id : '')) ; ?>

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
                            <input class="form-control" type="text" name="name" id="name" value="<?= $team->name ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="id_club">Club</label>
                            <select class="form-select" name="id_club" id="id_club">
                                <?php foreach ($clubs as $club) : ?>
                                    <option value="<?= $club['id'] ?>" <?= isset($team->id_club) && $team->id_club == $club['id']  ? 'selected' : '' ; ?> ><?= $club['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="id_season">Saison</label>
                            <select class="form-select" name="id_season" id="id_season">
                                <?php foreach ($seasons as $season) : ?>
                                    <option value="<?= $season['id'] ?>" <?= isset($team->id_season) && $team->id_season == $season['id'] ? 'selected' : '' ; ?>><?= $season['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="id_category">Catégorie</label>
                            <select class="form-select" name="id_category" id="id_category">
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category['id'] ?>" <?= isset($team->id_category) && $team->id_category == $category['id'] ? 'selected' : '' ; ?>><?=
                                        $category['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- END : INFOS DE L'ÉQUIPE -->
                    <!-- START : ZONE POUR AJOUTER UN CONTACT -->
                    <div class="mb-3">
                        <span class="btn btn-sm btn-secondary">
                            <i class="fas fa-plus"></i> Ajouter un contact
                        </span>
                    </div>
                    <div class="row">
                        <div class="">

                        </div>
                    </div>
                    <!-- END : ZONE POUR AJOUTER UN CONTACT -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <!-- START : COACHS -->
                            <div class="card mb-3">
                                <div class="card-header text-center">
                                    <div class="row">
                                        <div class="col">
                                            <span class="card-title h5">Coachs</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="btn btn-sm btn-primary" id="add-coach"><i class="fas fa-plus"></i> Ajouter un coach</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" id="zone-coach">

                                </div>
                            </div>
                            <!-- END : COACHS -->
                            <!-- START : JOUEURS -->
                            <div class="card mb-3">
                                <div class="card-header text-center">
                                    <span class="card-title h5">Joueurs</span>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>
                            <!-- END : JOUEURS -->
                        </div>
                        <div class="col-md-6">
                            <!-- START : CHAMPIONNATS ET COUPES -->
                            <div class="card mb-3">
                                <div class="card-header text-center">
                                    <span class="card-title h5">Championnats et coupes</span>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>
                            <!-- END : CHAMPIONNATS ET COUPES -->
                            <!-- START : MATCHS -->
                            <div class="card mb-3">
                                <div class="card-header text-center">
                                    <span class="card-title h5">Matchs</span>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>
                            <!-- END : MATCHS -->
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-sm btn-primary mx-2"><i class="fas fa-save"></i> Valider</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        let nbCoachs = 0 ;

        $('#add-coach').on('click', function(){
            nbCoachs ++;
            let row = `
                <div class="row mb-3 ">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-trash-alt text-danger"></i></span>
                        <select class="form-select" name="coachs[][id_coach]" id="select-coach-${nbCoachs}"></select>
                        </div>
                </div>

            `;

            $('#zone-coach').append(row);
            initAjaxSelect2(`#select-coach-${nbCoachs}`, {url:'/admin/member/search', searchFields: 'first_name,last_name,license_number'});
        });
    });
</script>
<?php $this->endSection() ; ?>