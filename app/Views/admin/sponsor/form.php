<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

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
                    <?= form_open_multipart('admin/sponsor/save'.(isset($sponsor) ? '/'.$sponsor['id'] : '' )) ?>
                    <div class="card-header">
                        <?php if (isset($sponsor) && $sponsor): ?>
                            <span class="card-title h3">Modification de <?=$sponsor['name'] ?></span>
                        <?php else: ?>
                            <span class="card-title h3">Création d'un sponsor</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <!-- IMAGE DU SPONSOR -->
                            <div class="col-md-6 text-center">
                                <img class="img-thumbnail mb-3" src="<?= (isset($sponsor['media_id'])) ? get_media_url( $sponsor['media_id'],'medium', base_url('/assets/img/default.png')) : '/assets/img/default.png'
                                ; ?>" title="image du sponsor" alt="image du sponsor " id="logoPreview">
                                <input class="form-control" type="file" name="logo" id="logo">
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <!-- NOM DU SPONSOR -->
                                    <div class="col">
                                        <label class="form-label" for="name">Nom<span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" id="name" value="<?=old('name',esc($sponsor['name'] ?? '')); ?>" required>
                                    </div>
                                </div>
                                <!-- SLOGAN DU SPONSOR -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="slogan">Slogan</label>
                                        <input class="form-control" type="text" name="slogan" id="slogan" value="<?=old('slogan', esc($sponsor['slogan'] ?? '')); ?>">
                                    </div>
                                </div>
                                <!-- COMMENTAIRES CONCERNANT LE SPONSOR -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="comments">Commentaires</label>
                                        <textarea class="form-control" name="comments" id="comments" rows="3"><?= old('comments',esc($sponsor['comments'] ?? ''));
                                            ?></textarea>
                                    </div>
                                </div>
                                <!-- BOUTONS D'AJOUT D'UNE SAISON ET D'UN CONTACT -->
                                <div class="row mb-3 row-buttons">
                                    <div class="col">
                                        <span class="btn btn-secondary" id="add-contact">
                                            <i class="fas fa-plus"></i> Ajouter un contact
                                        </span>
                                    </div>
                                    <div class="col">
                                        <span class="btn btn-primary" id="add-season">
                                            <i class="fas fa-plus"></i> Ajouter une saison
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if($sponsor) {
                        foreach($sponsor_seasons as $sponsor_season) : ?>
                        <!-- START : LIGNES CONCERNANT LES SAISONS -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <!-- SAISON -->
                                    <div class="col">
                                        <label class="form-label" for="season">Saison <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select" name="id_season" id="id_season" required>
                                                <?php foreach ($seasons as $season) : ?>
                                                    <option value="<?= $season['id'] ?>" <?= old('id_season') === $season['id'] ? 'selected' : (isset($team->id_season) && $team->id_season == $season['id'] ? 'selected':'') ;
                                                    ?>>
                                                        <?= $season['name']?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- IMPORTANCE DU SPONSOR -->
                                    <div class="col">
                                        <label class="form-label" for="rank"> Niveau d'importance <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select" name="rank" id="rank" required>
                                                <?php if(isset($sponsor['id_rank'])): ?>
                                                    <option value="<?= $sponsor['id_rank'] ?>" selected><?= $sponsor['rank_label'] ?></option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- DOTATION -->
                                <div class="row mb-3">
                                    <!-- TYPE DOTATION -->
                                    <div class="col-6">
                                        <label class="form-label" for="dotation_type">Type de dotation <span class="text-danger">*</span></label>
                                        <div class="input-group ">
                                            <select class="form-select" name="dotation_type" id="dotation_type" required>
                                                <?php if(isset($sponsor['id_dotation_type'])): ?>
                                                    <option value="<?= $sponsor['id_dotation_type'] ?>" selected><?= $sponsor['dotation_type'] ?></option>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                    </div>
                                    <!-- MONTANT DOTATION -->
                                    <div class="col-6">
                                        <label class="form-label" for="dotation_amount">Montant de dotation <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" name="dotation_amount" id="dotation_amount" min="0" value="<?=old('dotation_amount', esc
                                            ($sponsor['dotation_amount'] ??
                                                    '')); ?>"
                                                   required>
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- SPECIFICATIONS CONCERNANT LE SPONSOR POUR LA SAISON -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="specifications">Spécifications sur la saison</label>
                                        <textarea class="form-control" name="specifications" id="specifications" rows="5"><?= old('specifications',esc($sponsor['specifications'] ?? ''));
                                            ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : LIGNES CONCERNANT LES SAISONS -->
                        <?php endforeach;
                        }
                        ?>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Valider</button>
                    </div>
                    <?= form_close() ; ?>
                </div>
            </div>
        </div>
    </div>

<script>
    var baseUrl = "<?=base_url();?>";

    $(document).ready(function() {
        //Initialisation du select2 de la saison
        initAjaxSelect2(`#season`, {url:'/admin/sponsors-params/search-rank', searchFields: 'rank,label',separator:' - ', placeholder:'Rechercher un rang d\'importance'});

        //Initialisation du select2 du rang du sponsor
        initAjaxSelect2(`#rank`, {url:'/admin/sponsors-params/search-rank', searchFields: 'rank,label',separator:' - ', placeholder:'Rechercher un rang d\'importance'});

        //Initialisation du select2 du type de dotation
        initAjaxSelect2(`#dotation_type`, {url:'/admin/sponsors-params/search-type', searchFields: 'type', placeholder:'Rechercher un type de dotation'});
    });

</script>
<style>
    #logoPreview {
        max-height: 320px;
        width: auto;
        object-fit: contain;
    }

    .row-buttons {
        height : 6rem;
    }

    .row-buttons .col {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<?php $this->endsection() ; ?>