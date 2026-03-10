<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ?>

<?php echo form_open_multipart('/admin/club/save' . (isset($club) && $club ? "/". $club['id'] : "")); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <span class="card-title h3">Création d'un club</span>
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
                                    <input class="form-control" type="text" name="code" id="code" value="<?= esc($club['code'] ?? '') ; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="name">Nom du club <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" id="name" value="<?= esc($club['name'] ?? '') ; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="color_1">Couleur 1</label>
                                    <input class="form-control" type="text" name="color_1" id="color_1" value="<?= (isset($club['color_1'])) ? esc($club['color_1']) : '' ; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="color_2">Couleur 2</label>
                                    <input class="form-control" type="text" name="color_2" id="color_2" value="<?= isset($club['color_2']) ? esc($club['color_2']) : '' ; ?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END : INFO GENERALES DU CLUB -->
                    <div class="row mb-3">
                        <!-- START : EQUIPES -->
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header text-center">
                                    <span class="card-title fw-bold h5">Équipes du club</span>
                                </div>
                                <div class="card-body">

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

                                </div>
                            </div>

                        </div>
                        <!-- END : GYMNASES -->
                    </div>

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
    // Initialiser l'aperçu du logo
    initImagePreview('#logo', '#logoPreview', '<?= esc(base_url('/assets/img/default.png'),'js') ?>', 2);

    //Apparition du bouton de suppression de l'image
    $('.logo-hover').on('mouseenter mouseleave', function(){
        $(this).find('.position-absolute').fadeToggle(50);
    });

    // Action du clic sur le bouton de suppression d'une image
    $('.delete-logo').on('click', function(e){
        e.preventDefault();
        let logoId = $('#logoPreview').data('logo-id') ;
        console.log(logoId);
        $(this).append(`<input type="hidden" name="delete-logo" value="${logoId}" id='delete-logo' />`);
        $('#logoPreview').attr('src', "<?= base_url('/assets/img/default.png') ?>");
        $('#logo').val('');
    });
})

</script>
<style>
    #logoPreview {
       max-height: 320px;
    }
</style>
<?php echo form_close() ?>

<?php $this->endSection() ?>
