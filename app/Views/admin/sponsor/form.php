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
                    <?= form_open_multipart('admin/sponsor/save'.(isset($sponsor) ? '/'.$sponsor->id : '' )) ?>
                    <div class="card-header">
                        <?php if (isset($sponsor) && $sponsor): ?>
                            <span class="card-title h3">Modification de <?=$sponsor->name ?></span>
                        <?php else: ?>
                            <span class="card-title h3">Création d'un sponsor</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <!-- INPUTS POUR SUPPRIMER ET VISUALISER LA MEA -->
                            <?php $sponsorLogo = isset($sponsor) ? $sponsor->getLogo() : null;
                                if(isset($sponsorLogo)) :?>
                            <div class="col-auto d-flex flex-column justify-content-center">
                                <div id="input-group-logo">
                                    <div class="my-3">
                                        <a href="" class="btn btn-danger text-light" id="delete-logo" data-id="<?= $sponsor->logo_id ?>">
                                            <i class="fas fa-trash-alt">Supprimer</i>
                                        </a>
                                    </div>
                                    <div class="my-3">
                                        <a href="<?= $sponsorLogo->file_path ?>" data-lightbox="sponsor-logo" id="visualize-button-logo" class="btn btn-success text-light visualize-img">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <!-- LOGO DU SPONSOR -->
                            <div class="col text-center">
                                <img class="img-thumbnail mb-3" src="<?= (isset($sponsor->logo_id)) ? get_media_url( $sponsor->logo_id,'medium', base_url('/assets/img/default.png')) : '/assets/img/default.png'; ?>" title="logo du sponsor" alt="logo du sponsor " id="logoPreview">
                                <input class="form-control" type="file" name="logo" id="logo">
                                <span class="fst-italic fw-normal">Formats acceptés: .jpg, .png, .gif, .webp (maximum 2MB)</span>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <!-- NOM DU SPONSOR -->
                                    <div class="col">
                                        <label class="form-label" for="name">Nom<span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" id="name" value="<?=old('name',esc($sponsor->name ?? '')); ?>" required>
                                    </div>
                                </div>
                                <!-- SLOGAN DU SPONSOR -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="slogan">Slogan</label>
                                        <input class="form-control" type="text" name="slogan" id="slogan" value="<?=old('slogan', esc($sponsor->slogan ?? '')); ?>">
                                    </div>
                                </div>
                                <!-- COMMENTAIRES CONCERNANT LE SPONSOR -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="comments">Commentaires</label>
                                        <textarea class="form-control" name="comments" id="comments" rows="3"><?= old('comments',esc($sponsor->comments ?? ''));
                                            ?></textarea>
                                    </div>
                                </div>
                                <!-- BOUTONS D'AJOUT D'UNE SAISON ET D'UN CONTACT -->
                                <div class="row mb-3 row-buttons">
                                    <div class="col">
                                        <span class="btn btn-primary" id="add-season">
                                            <i class="fas fa-plus"></i> Ajouter une saison
                                        </span>
                                    </div>
                                    <div class="col">
                                        <span class="btn btn-secondary" id="add-contact">
                                            <i class="fas fa-plus"></i> Ajouter un contact
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- START : LIGNES CONCERNANT LES SAISONS -->
                        <div id="zone-season-sponsor">
                            <?php
                            $cptSeason = 0;
                            if($sponsor && isset($sponsor->seasons)){
                            foreach($sponsor->seasons as $sponsor_season) :
                                $cptSeason++ ?>
                            <div class="row mb-3 row-season-sponsor bg-primary-subtle rounded">
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <input class="season-sponsor-id-input" type="hidden" name="id-season-sponsor-<?=$cptSeason ?>[id]" id="id-season-sponsor-<?=$cptSeason ?>" value="<?=
                                                old
                                                ('id-season-sponsor-.'
                                                .$cptSeason.'.id',esc($sponsor_season['id']??''))?>"
                                                <!-- SAISON -->
                                                <div class="col">
                                                    <label class="form-label" for="id-season-<?=$cptSeason ?>">Saison <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-select" name="season[<?=$cptSeason ?>][id_season]" id="id-season-<?=$cptSeason ?>" required>
                                                            <?php if(isset($sponsor_season['id_season'])): ?>
                                                                <option value="<?= $sponsor_season['id_season'] ?>" selected><?= $sponsor_season['season_name'] ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- IMPORTANCE DU SPONSOR -->
                                                <div class="col">
                                                    <label class="form-label" for="rank-<?=$cptSeason ?>"> Niveau d'importance <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-select" name="season[<?=$cptSeason ?>][rank]" id="rank-<?=$cptSeason ?>" required>
                                                            <?php if(isset($sponsor_season['id_rank'])): ?>
                                                                <option value="<?= $sponsor_season['id_rank'] ?>" selected><?= $sponsor_season['rank_label'] ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- DOTATION -->
                                            <div class="row mb-3">
                                                <!-- TYPE DOTATION -->
                                                <div class="col-6">
                                                    <label class="form-label" for="dotation_type-<?=$cptSeason ?>">Type de dotation <span class="text-danger">*</span></label>
                                                    <div class="input-group ">
                                                        <select class="form-select" name="season[<?=$cptSeason ?>][dotation_type]" id="dotation_type-<?=$cptSeason ?>" required>
                                                            <?php if(isset($sponsor_season['id_dotation_type'])): ?>
                                                                <option value="<?= $sponsor_season['id_dotation_type'] ?>" selected><?= $sponsor_season['dotation_type'] ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>

                                                </div>
                                                <!-- MONTANT DOTATION -->
                                                <div class="col-6">
                                                    <label class="form-label" for="dotation_amount-<?=$cptSeason ?>">Montant de dotation <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="number" name="season[<?=$cptSeason ?>][dotation_amount]" id="dotation_amount-<?=$cptSeason ?>" min="0" value="<?=old('dotation_amount', esc
                                                        ($sponsor_season['dotation_amount'] ??
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
                                                    <label class="form-label" for="specifications-<?=$cptSeason ?>">Spécifications sur la saison</label>
                                                    <textarea class="form-control" name="season[<?=$cptSeason ?>][specifications]" id="specifications-<?=$cptSeason ?>" rows="5"><?= old('specifications',esc($sponsor_season['specifications'] ?? ''));
                                                        ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-trash-alt text-danger btn-delete-season fs-2"></i>
                                </div>
                            </div>
                            <?php endforeach;
                            }
                            ?>
                            <div id="zone-removed-season-sponsor">

                            </div>
                        </div>
                        <!-- END : LIGNES CONCERNANT LES SAISONS -->
                        <!-- START : ZONE POUR AJOUTER UN CONTACT -->
                        <div id="zone-contact">
                            <?php if(isset($sponsor->contacts)){
                                $nbContacts = 0;
                                foreach ($sponsor->contacts as $contact) {
                                    $nbContacts++; ?>

                                    <!-- START : LIGNE CONTACTS AVEC LES INPUTS -->
                                    <?= $this->setData([
                                            'contact'=>$contact,
                                            'nbContacts'=>$nbContacts
                                    ])->include('admin/contact/contact-inputs',null,false)
                                    ?>
                                    <!-- END : LIGNE CONTACTS AVEC LES INPUTS -->

                                <?php } ?>
                            <?php } ?>

                            <!-- START : ZONE POUR LES NOUVEAUX CONTACTS-->
                            <template id="contact-template">
                                <?= $this->setData([
                                        'nbContacts'=>'__NB_CONTACTS__',
                                ])->include('admin/contact/contact-inputs')
                                ?>
                            </template>
                            <!-- END : ZONE POUR LES NOUVEAUX CONTACTS-->

                            <!-- START : INPUT HTML POUR STOCKER LES ID DES CONTACTS SUPPRIMES -->
                            <div id="zone-removed-contacts">

                            </div>
                            <!-- END : INPUT HTML POUR STOCKER LES ID DES CONTACTS SUPPRIMES -->
                        </div>
                        <!-- END : ZONE POUR AJOUTER UN CONTACT -->
                        <!-- START : IMAGES -->
                        <div id="zone-images">
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header h5 text-center">
                                            Images du sponsor
                                        </div>
                                        <div class="card-body">
                                            <input class="form-control" type="file" name="sponsor_images[]" id="images" placeholder="Ajouter des images" multiple>
                                            <span class="fst-italic fw-normal">Formats acceptés: .jpg, .png, .gif, .webp (maximum 2MB)</span>
                                            <div class="row row-cols-2 row-cols-md-4 row-cols-xl-6 my-3" id="zone-images">
                                                <?php $sponsorImages = isset($sponsor) ? model('MediaModel')->where('entity_id', $sponsor->id)->where('entity_type', 'sponsor_image')->findAll() : null;
                                                if (!empty($sponsorImages)) {
                                                    foreach($sponsorImages as $image) {?>
                                                        <div class="col mb-3">
                                                            <div class="position-relative img-hover">
                                                                <div class="position-absolute img-thumbnail" style="height:100%;width:100%;background-color:rgb(0,0,0,0.3);display:none;">
                                                                    <div class="d-flex justify-content-around align-items-center" style="height:100%;width:100%;">
                                                                        <a href="" class="btn btn-danger text-light delete-img" data-id="<?=$image->id?>">
                                                                            <i class="fas fa-trash-alt">Supprimer</i>
                                                                        </a>
                                                                        <a href="<?= $image->file_path ?>" data-lightbox="sponsor-images" class="btn btn-success text-light visualize-img">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <img class="img-thumbnail image" src="<?=get_media_url(intval($image->id),'medium','/assets/img/default.png');?>">
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : IMAGES -->
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
    let cptSeason = ('#zone-season-sponsor').length;
    let nbContacts = $('#zone-contact .row-contact').length ;

    $(document).ready(function() {

        //GESTION DU LOGO
        //Gestion de la prévisualisation
        //définition des variables
        const inputLogo = $('#logo');
        const logoPreview = $('#logoPreview');
        const visualizeLogo = $('#visualize-button-logo');

        //création de la fonction avec l'évènement déclencheur
        inputLogo.on('change', function () {
            //création variable qui récupère le fichier uploadé
            const file = this.files[0];
            // définition de son URL
            const fileUrl = URL.createObjectURL(file);
            //apparition du fichier uploadé sur le zone prévue pour le logo
            logoPreview.attr('src',fileUrl);
            //changement du lien pour pouvoir l'ouvrir avec lightbox
            visualizeLogo.attr('href',fileUrl);
            //réapparition des boutons de suppression et de visualisation s'ils étaient cachés
            $('#input-group-logo').show();
        });

        //Suppression du logo du sponsor
        //Action du clic sur le bouton de suppression de la MEA
        $('#delete-logo').on('click', function(e){
            e.preventDefault();
            let idLogo =$(this).data('id');
            $('#logo').append(`<input type="hidden" name="delete-logo" value="${idLogo}" >`)
            $('#logoPreview').attr('src',"<?=base_url('/assets/img/default.png') ?>");
            $('#input-group-logo').hide();
            $(inputLogo).val('');
        })

        //GESTION DES SAISONS DE SPONSORING
        //Boucles pour initialiser les select2 pour les saisons de sponsoring
        for (i=1 ; i<=cptSeason; i++) {
            //Initialisation du select2 de la saison
            initAjaxSelect2(`#id-season-${i}`, {url:'/admin/season/search', searchFields: 'name,start_date,end_date', placeholder:'Rechercher une saison'});

            //Initialisation du select2 du rang du sponsor
            initAjaxSelect2(`#rank-${i}`, {url:'/admin/sponsors-params/search-rank', searchFields: 'rank,label',separator:' - ', placeholder:'Rechercher un rang d\'importance'});

            //Initialisation du select2 du type de dotation
            initAjaxSelect2(`#dotation_type-${i}`, {url:'/admin/sponsors-params/search-type', searchFields: 'type', placeholder:'Rechercher un type de dotation'});
        }

        //Fonction pour ajouter une saison
        $('#add-season').on('click', function(){
            cptSeason++;
            let row = `
            <div class="row mb-3 row-season-sponsor bg-primary-subtle rounded">
                <div class="col-11">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <!-- SAISON -->
                                <div class="col">
                                    <label class="form-label" for="season">Saison <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-select" name="season[${cptSeason}][id_season]" id="id-season-${cptSeason}" required>

                                        </select>
                                    </div>
                                </div>
                                <!-- IMPORTANCE DU SPONSOR -->
                                <div class="col">
                                    <label class="form-label" for="rank"> Niveau d'importance <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-select" name="season[${cptSeason}][rank]" id="rank-${cptSeason}" required>

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
                                        <select class="form-select" name="season[${cptSeason}][dotation_type]" id="dotation_type-${cptSeason}" required>

                                        </select>
                                    </div>

                                </div>
                                <!-- MONTANT DOTATION -->
                                <div class="col-6">
                                    <label class="form-label" for="dotation_amount">Montant de dotation <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="season[${cptSeason}][dotation_amount]" id="dotation_amount-${cptSeason}" min="0" required>
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
                                    <textarea class="form-control" name="season[${cptSeason}][specifications]" id="specifications-${cptSeason}" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1 d-flex align-items-center justify-content-center">
                    <i class="fas fa-trash-alt text-danger btn-delete-season fs-2"></i>
                </div>
            </div>
            `;
            $('#zone-season-sponsor').prepend(row);

            //Initialisation des select2
            //Initialisation du select2 de la saison
            initAjaxSelect2(`#id-season-${cptSeason}`, {url:'/admin/season/search', searchFields: 'name,start_date,end_date', placeholder:'Rechercher une saison'});

            //Initialisation du select2 du rang du sponsor
            initAjaxSelect2(`#rank-${cptSeason}`, {url:'/admin/sponsors-params/search-rank', searchFields: 'rank,label',separator:' - ', placeholder:'Rechercher un rang d\'importance'});

            //Initialisation du select2 du type de dotation
            initAjaxSelect2(`#dotation_type-${cptSeason}`, {url:'/admin/sponsors-params/search-type', searchFields: 'type', placeholder:'Rechercher un type de dotation'});
        })

        //Fonction pour supprimer une saison
        $(document).on('click', '.btn-delete-season', function(){
            cptSeason--;
            let rowSeasonSponsor = $(this).closest('.row-season-sponsor')
            let idRemovedSeasonSponsor = rowSeasonSponsor.find('.season-sponsor-id-input').val();
            console.log(idRemovedSeasonSponsor);
            $(this).closest('.row-season-sponsor').remove();
            let inputRemovedSeasonSponsor = `
            <input type="hidden" name="removed-seasons-sponsor[]" value="${idRemovedSeasonSponsor}">
            `;
            $('#zone-removed-season-sponsor').append(inputRemovedSeasonSponsor);
        })

        //Gestion de l'ajout d'un contact
        $('#add-contact').on('click',function(){
            nbContacts ++;
            let row = $('#contact-template').html();
            row = row.replaceAll('__NB_CONTACTS__', nbContacts);
            $('#zone-contact').append(row);
        })

        //Gestion de la suppression d'un contact
        $('#zone-contact').on('click', '.delete-contact-button' ,function(){
            let rowContact = $(this).closest('.row-contact');
            let idRemovedContact = rowContact.find('.contact-id-input').val();
            nbContacts --;
            rowContact.remove();
            let inputRemovedContacts = `
            <input type="hidden" name="removed-contacts[]" value="${idRemovedContact}">
            `;
            $('#zone-removed-contacts').append(inputRemovedContacts);
        })

        //GESTION DES IMAGES
        //Gestion de la prévisualisation des images
        //définition des variables
        const inputImages = $('#images');

        //création de la fonction avec l'évènement déclencheur
        inputImages.on('change', function () {
            const filesImg = this.files;
            $.each(filesImg,function (index,fileImg){
                let fileUrl = URL.createObjectURL(fileImg);
                let colImg = `
                    <div class="col mb-3">
                        <div class="position-relative img-hover">
                            <div class="position-absolute img-thumbnail" style="height:100%;width:100%;background-color:rgb(0,0,0,0.3);display:none;">
                                <div class="d-flex justify-content-around align-items-center" style="height:100%;width:100%;">
                                    <a href="" class="btn btn-danger text-light delete-img">
                                        <i class="fas fa-trash-alt">Supprimer</i>
                                    </a>
                                    <a href="${fileUrl}" data-lightbox="sponsor-images" class="btn btn-success text-light visualize-img">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <img class="img-thumbnail image" src="${fileUrl}">
                        </div>
                    </div>
                    `;
                $('#zone-images').append(colImg);
            });
        });

        //Apparition des boutons de gestion d'une image à son survol
        $('#zone-images').on('mouseenter mouseleave','.img-hover', function () {
            $(this).find('.position-absolute').fadeToggle(50);
        });

        // Action du clic sur le bouton de suppression d'une image
        $('#zone-images').on('click','.delete-img', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
            let $col = $(this).closest('.col');
            if(id){
                $col.append(`<input type="hidden" name="deleted-img[]" value="${id}" >`)
                $col.hide();
            } else {
                $col.remove();
            }
        });
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

    .btn-delete-season:hover, .delete-contact-button:hover{
        scale:1.20;
        cursor: pointer;
    }

    .image{
        aspect-ratio: 1;
        object-fit: cover;
    }
</style>

<?php $this->endsection() ; ?>