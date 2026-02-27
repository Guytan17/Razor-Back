<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<?php echo form_open('admin/gym/save'. (isset($gym) && $gym ? '/'.$gym['id'] : '')) ?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <?php if($gym){ ?>
                        <span class="card-title h3">Modification de <?= $gym['name'] ?></span>
                    <?php }else{ ?>
                        <span class="card-title h3">Création d'un gymnase</span>
                    <?php } ?>

                </div>
                <div class="card-body">
                    <!-- START : INFOS DU GYMNASE -->
                    <div class="row">
                        <!-- START: INPUTS GYMNASE -->
                        <div class="col-md-6 mb-3">
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="name">Nom du Gymnase</label>
                                    <input class="form-control" type="text" name="name" id="name" value="<?=esc($gym['name'] ?? '') ;?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="fbi_code">Code FBI</label>
                                    <input class="form-control" type="text" name="fbi_code" id="fbi_code" value="<?=esc($gym['fbi_code'] ?? '') ;?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="hidden" name="id_address" value="<?=esc($gym['id_address'] ?? '');?>"">
                                    <label class="form-label" for="address_1">Adresse</label>
                                    <input class="form-control" type="text" name="address_1" id="address_1" value="<?=esc($gym['address_1'] ?? '');?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="address_2">Complément d'adresse <span class="fst-italic">(facultatif)</span></label>
                                    <input class="form-control" type="text" name="address_2" id="address_2" value="<?=esc($gym['address_2'] ?? '');?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="select-city">Ville</label>
                                    <div class="input-group">
                                        <select class="form-select" name="city" id="select-city">
                                            <?php if (isset($gym['id_city'])) { ?>
                                            <option value="<?= $gym['id_city']?>"> <?= $gym['label'].' '.$gym['zip_code'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: INPUTS GYMNASE -->
                        <!-- START: ZONE GOOGLE MAP -->
                        <div class="col-md-6 mb-3">
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="location-map">
                                        <iframe
                                                width="100%"
                                                height="100%"
                                                frameborder="0" style="border:0"
                                                referrerpolicy="no-referrer-when-downgrade"
                                                <?php if (isset($gym) && !empty($gym['gps_location'])) { ?>
                                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCsBJYByuOScPSGFRTFh9Xeu07lQXzGoPY
                                                    &q=<?= esc($gym['gps_location']) ?>"
                                                <?php } elseif (isset($gym) && empty($gym['gps_location'])) { ?>
                                                    src="https://www.google.com/maps/embed/v1/search?key=AIzaSyCsBJYByuOScPSGFRTFh9Xeu07lQXzGoPY
                                                        &q=<?=(esc($gym['name']) ?? '').'+'.(esc($gym['address_1']) ?? '').'+'.(esc($gym['zip_code']) ?? '').'+'.(esc($gym['label']) ?? '')?>"
                                                <?php } else { ?>
                                                    src="https://www.google.com/maps/embed/v1/search?key=AIzaSyCsBJYByuOScPSGFRTFh9Xeu07lQXzGoPY
                                                    &q=+
                                                    &center=46.14556311478873,-1.140210252809791
                                                    &zoom=10"
                                                <?php } ?>
                                                allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="gps_location">
                                        Coordonnées GPS
                                        <?php if(isset($gym) && empty($gym['gps_location'])) { ?>
                                            (⚠️ Point Google Map non-vérifié)
                                        <?php } ?>
                                    </label>
                                    <input class="form-control" type="text" name="gps_location" id="gps_location" value="<?=esc($gym['gps_location'] ?? '') ;?>">
                                </div>
                            </div>
                        </div>
                        <!-- END: ZONE GOOGLE MAP -->
                    </div>
                    <!-- END : INFOS DU GYMNASE -->
                    <!-- ZONE : ÉLÉMENTS RATTACHÉS AU GYMNASE -->
                    <div class="row">
                        <!-- START : CLUBS -->
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header text-center">
                                    <span class="card-title fw-bold h5">Clubs utilisant ce gymnase</span>
                                </div>
                                <div class="card-body" id="zone-club">
                                    <div class="row mb-3">
                                        <div class="col p-3">
                                            <div class="input-group">
                                                <select class="form-select select-club" id="select-club">
                                                </select>
                                                <span class="input-group-text btn btn-sm btn-primary d-flex align-items-center" id="add-club"><i class="fas fa-plus"></i> Ajouter</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 overflow-auto">
                                        <div class="col" id="zone-club-list">
                                            <?php if(isset($gym['clubs'])){
                                                $cpt_clubs = 0 ;
                                                foreach ($gym['clubs'] as $club) :
                                                    $cpt_clubs ++ ?>
                                                    <div class="row mb-3 row-club">
                                                        <div class="col">
                                                            <div class="card card-club">
                                                                <div class="card-body p-1 d-flex align-items-center">
                                                                    <div class="row w-100">
                                                                        <div class="col-auto g-start-1">
                                                                            <span class="fs-4" id="delete-club-<?= $cpt_clubs ?>"><i class="fas fa-trash-alt text-danger
                                                                            delete-club-button"></i></span>
                                                                        </div>
                                                                        <div class="col d-flex align-items-center g-start-2">
                                                                            <span class="fw-semibold">
                                                                                <?= $club['name']?> -
                                                                                <span class="fst-italic"><?=$club['code']?></span>
                                                                            </span>
                                                                        </div>
                                                                        <div class="col-auto d-flex align-items-center" >
                                                                            <div class="form-check">
                                                                                <label class="form-label m-0" for="main-gym-<?= $cpt_clubs ?>">Principal</label>
                                                                                <input class="form-check-input main-club-check" type="checkbox" name="clubs[<?= $cpt_clubs ?>][main_gym]"
                                                                                       id="main-gym-<?= $cpt_clubs ?>" <?= $club['main_gym'] == 1 ? 'checked' : '' ?>>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="clubs[<?= $cpt_clubs ?>][id]" value="<?= $club['id_club'] ?>">
                                                    </div>
                                                <?php endforeach;
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END : CLUBS -->
                        <!-- START : MATCHS -->
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header text-center">
                                    <span class="card-title fw-bold h5">Matchs récents</span>
                                </div>
                                <div class="card-body" id="zone-game">

                                </div>
                            </div>
                        </div>
                        <!-- START : MATCHS -->
                    </div>
                    <!-- END : ÉLÉMENTS RATTACHÉS AU GYMNASE -->
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-sm btn-primary mx-2"><i class="fas fa-save"></i> Valider</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close() ; ?>
<script>
    $(document).ready(function () {
        baseUrl = "<?= base_url(); ?>";
        let nbClubs = $('#zone-club .card-club').length ;

        //Initialisation du select des villes
        initAjaxSelect2(`#select-club`, {url:'/admin/club/search',searchFields:'label,zip_code',additionalFields:['department_number','department_name'],placeholder:'Rechercher une ville'});

        //Initialisation du select des clubs
        initAjaxSelect2('#select-club', {url:'/admin/club/search',searchFields:'name',additionalFields:['code'],placeholder:'Rechercher un club'});

        //Gestion ajout coach
        $('#add-club').on('click', function(){
            let selectedClub = $('#select-club').select2('data');

            // si aucun club n'est sélectionné lors du clic, on bloque la création de la row
            if (!selectedClub.length) {
                return;
            }

            //Si un membre est sélectionné, on
            nbClubs ++;
            let club = selectedClub[0];
            console.log('club:'+club);
            let row = `
            <div class="row mb-3 row-club">
                <div class="col">
                    <div class="card card-club">
                        <div class="card-body p-1 d-flex align-items-center">
                            <div class="row w-100">
                               <div class="col-auto">
                                   <span class="fs-4" id="delete-club-${nbClubs}"><i class="fas fa-trash-alt text-danger delete-club-button"></i></span>
                               </div>
                                <div class="col d-flex align-items-center">
                                    <span class="fw-semibold" >${club.text} - <span class="fst-italic">${club.code}</span></span>
                                </div>
                                <div class="col-auto d-flex align-items-center">
                                    <div class="form-check">
                                        <label class="form-label m-0" for="main-gym-${nbClubs}">Principal</label>
                                        <input class="form-check-input main-club-check" type="checkbox" name="clubs[${nbClubs}][main_gym]" id="main-gym-${nbClubs}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="clubs[${nbClubs}][id]" value="${club.id}">
            </div>
            `;

            $('#zone-club-list').prepend(row);
            $('#select-club').empty();
        });

        // Gestion suppression club
        $('#zone-club').on('click' , '.delete-club-button', function(){
            nbClubs --;
            $(this).closest('.row-club').remove();
        })


    });
</script>
<style>
  .location-map {
      height:328px;
  }

  .select2-result-item__additionalFields {
      font-size: 0.85em;
      font-style: italic;
  }

  .delete-club-button:hover,.delete-game-button:hover {
      scale:1.2;
      cursor: pointer;
  }
</style>
<?php $this->endSection() ; ?>