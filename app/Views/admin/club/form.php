<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ?>

<?php echo form_open_multipart('/admin/club/save' . (isset($club) && $club ? "/". $club['id'] : "")); ?>

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
                <div class="card-header text-center">
                    <?php if (isset($club) && $club): ?>
                    <span class="card-title h3">Modification de <?=$club['name'] ?></span>
                    <?php else: ?>
                    <span class="card-title h3">Création d'un club</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <!-- START : INFO GENERALES DU CLUB -->
                    <div class="row mb-3">
                        <div class="col-md-6 text-center">
                            <div class="row d-flex justify-content-center">
                                <div class="col-auto position-relative p-0 m-0 logo-hover" id="zone-logo">
                                    <div class="position-absolute img-thumbnail" style="height:100%;width:100%;background-color: rgb(0,0,0,0.3);display:none">
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            <div class="btn btn-danger text-white delete-logo">
                                                <i class="fas fa-trash-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <img class="img-thumbnail" src="<?= (isset($club['media_id'])) ? get_media_url( $club['media_id'],'medium', base_url('/assets/img/default.png')) : '/assets/img/default.png'
                                    ; ?>" title="image du club" alt="image du
                                    club " id="logoPreview" data-logo-id="<?= $club['media_id'] ?? '' ; ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="logo">Logo du club</label>
                                    <input class="form-control" type="file" name="logo" id="logo">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="code">Code FBI du club <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="code" id="code" value="<?= old('code',esc($club['code'] ?? '')); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="name">Nom du club <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" id="name" value="<?= old('name',esc($club['name'] ?? '')); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="color_1">Couleur 1</label>
                                    <input class="form-control" type="text" name="color_1" id="color_1" value="<?= old('color_1',esc($club['color_1'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="color_2">Couleur 2</label>
                                    <input class="form-control" type="text" name="color_2" id="color_2" value="<?= old('color_2',esc($club['color_2'] ?? '')); ?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END : INFO GENERALES DU CLUB -->
                    <?php if(isset($club)){ ?>
                        <div class="row mb-3">
                            <!-- START : EQUIPES -->
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header text-center">
                                        <span class="card-title fw-bold h5">Équipes du club</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col p-3">
                                                <div class="input-group">
                                                    <select class="form-select select-team" id="select-team">
                                                    </select>
                                                    <span class="input-group-text btn btn-sm btn-primary d-flex align-items-center" id="add-team"><i class="fas fa-plus"></i> Ajouter</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php if(!empty($club['teams'])){
                                        $cpt_teams = 0;
                                        foreach($club['teams'] as $team):
                                            $cpt_teams++; ?>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <div class="card">
                                                        <a class="card-team" href="<?=base_url('admin/team/form/'.$team->id)?>">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col text-center">
                                                                        <span class="fw-bold"><?= $team->name.' - ' ?></span> <span class="fw-semibold fst-italic"> <?= $team->category_name.' - 
                                                                        '.$team->season_name ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
                                    } else {?>
                                        <div class="row">
                                            <div class="col">
                                                <span class="fst-italic">Il n'y a pas d'équipe enregistrée pour ce club</span>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- END : EQUIPES -->
                            <!-- START : GYMNASES -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header text-center">
                                        <span class="card-title fw-bold h5">Gymnases du club</span>
                                    </div>
                                    <div class="card-body">
                                        <?php if(!empty($club['gyms'])){
                                            $cpt_gyms = 0;
                                            foreach($club['gyms'] as $gym):
                                                $cpt_gyms++; ?>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <div class="card">
                                                            <a class="card-team" href="<?=base_url('admin/gym/form/'.$gym['id'])?>">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col text-center">
                                                                            <span class="fw-bold"><?= $gym['gym_name']?></span><span class="fw-semibold fst-italic"><?=(isset($gym['gym_fbi_code'])&&!empty($gym['gym_fbi_code'])
                                                                                        ?' - '.$gym['gym_fbi_code']:'').
                                                                                ($gym['main_gym']==1?' - Gymnase 
                                                                            principal':'') ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col text-center">
                                                                             <span class=""><?=$gym['gym_address'].', '.$gym['city']?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;
                                        }else {?>
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fst-italic">Aucun gymnase n'est rattaché à ce club</span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- END : GYMNASES -->
                        </div>
                    <?php } ?>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary mx-2"><i class="fas fa-save"></i> Valider</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    let logoId = $('#logoPreview').data('logo-id') ?? null;

    let hasLogo = logoId ? true : false;

    //Apparition et disparition du bouton de suppression de l'image
    $('.logo-hover').on('mouseenter', function() {
        if(hasLogo) {
            $(this).find('.position-absolute').fadeIn(50);
        }
    }).on('mouseleave', function() {
        $(this).find('.position-absolute').fadeOut(50);
    });

    $('#logo').on('change',function () {
        hasLogo = true;
    })

    // Initialiser l'aperçu du logo
    initImagePreview('#logo', '#logoPreview', '<?= esc(base_url('/assets/img/default.png'),'js') ?>', 2);

    // Action du clic sur le bouton de suppression d'une image
    $('.delete-logo').on('click', function(e){
        e.preventDefault();
        $(this).append(`<input type="hidden" name="delete-logo" value="${logoId}" id='delete-logo' />`);
        $('#logoPreview').attr('src', "<?= base_url('/assets/img/default.png') ?>");
        $('#logo').val('');
        $(this).closest('.position-absolute').fadeOut(50);
        hasLogo = false;
    });

    //initialisation select-team
    initAjaxSelect2(`#select-team`, {url:'/admin/team/search', searchFields: 'name', separator :' - ',additionalFields : 'season_name,category_name,club_name', placeholder:'Rechercher une équipe'});
})

</script>
<style>
    #logoPreview {
       max-height: 320px;
    }

    .card-team{
        text-decoration: none;
        color: black;
    }

    .card-team:hover{
        scale:1.05;
        cursor: pointer;
    }
</style>
<?php echo form_close() ?>

<?php $this->endSection() ?>
