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
                                    <span class="card-title h5">Coachs</span>
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
                                            <?php if(isset($team->coachs)){
                                                $cpt_coachs = 0 ;
                                                foreach ($team->coachs as $coach) :
                                                    $cpt_coachs ++ ?>
                                                    <div class="row row-coach">
                                                        <div class="col">
                                                            <div class="card card-coach">
                                                                <div class="card-body p-1 d-flex align-items-center">
                                                                    <div class="row">
                                                                        <div class="col-auto">
                                                                            <span class="fs-4" id="delete-coach-<?= $cpt_coachs ?>"><i class="fas fa-trash-alt text-danger delete-coach-button"></i></span>
                                                                        </div>
                                                                        <div class="col d-flex align-items-center">
                                                                            <span class="fw-semibold"><?= $coach['coach_first_name'].' '.$coach['coach_last_name'].' - '.$coach['coach_license_number']?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="coachs[]" value="<?= $coach['id_member'] ?>">
                                                    </div>
                                                <?php endforeach;
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END : COACHS -->
                            <!-- START : JOUEURS -->
                            <div class="card mb-3">
                                <div class="card-header text-center">
                                    <span class="card-title h5">Joueurs</span>
                                </div>
                                <div class="card-body" id="zone-player">

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
                                <div class="card-body" id="zone-division">

                                </div>
                            </div>
                            <!-- END : CHAMPIONNATS ET COUPES -->
                            <!-- START : MATCHS -->
                            <div class="card mb-3">
                                <div class="card-header text-center">
                                    <span class="card-title h5">Matchs</span>
                                </div>
                                <div class="card-body" id="zone-game">

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

        let nbCoachs = $('#zone-coach .card-coach').length ;
        console.log(nbCoachs);

        //initialisation select-coach
        initAjaxSelect2(`#select-coach`, {url:'/admin/member/search', searchFields: 'first_name,last_name',  additionalFields :'license_number', placeholder:'Rechercher un membre'});

        //Gestion ajout coach
        $('#add-coach').on('click', function(){
            let selectedMember = $('#select-coach').select2('data');
            console.log(selectedMember);

            // si aucun membre n'est sélectionné lors du clic, on bloque la création de la row
            if (!selectedMember.length) {
                return;
            }

            //Si un membre est sélectionné, on
            nbCoachs ++;
            let coach = selectedMember[0];
            console.log(coach);
            let row = `
            <div class="row row-coach">
                <div class="col">
                    <div class="card card-coach">
                        <div class="card-body p-1 d-flex align-items-center">
                            <div class="row">
                               <div class="col-auto">
                                   <span class="fs-4" id="delete-coach-${nbCoachs}"><i class="fas fa-trash-alt text-danger delete-coach-button"></i></span>
                               </div>
                                <div class="col d-flex align-items-center">
                                    <span class="fw-semibold" >${coach.text} - ${coach.license_number}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="coachs[]" value="${coach.id}">
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
    });
</script>
<style>
    #zone-coach-list,#zone-division-list {
        max-height : 250px;
    }

    .row-coach {
        margin-bottom: 1rem;
    }

    .delete-coach-button:hover {
        scale:1.2;
        cursor: pointer;
    }
</style>
<?php $this->endSection() ; ?>
}