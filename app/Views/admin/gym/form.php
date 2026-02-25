<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<?php echo form_open('admin/gym/save'. (isset($gym) ? '/'.$gym['id'] : '')) ?>
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
                                    <input class="form-control" type="text" name="name" id="name" value="<?=(esc($gym['name']) ?? '' ) ;?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="fbi_code">Code FBI</label>
                                    <input class="form-control" type="text" name="fbi_code" id="fbi_code" value="<?=(esc($gym['fbi_code']) ?? '' ) ;?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="hidden" name="id_address" value="<?=(esc($gym['id_address']) ?? '' ) ;?>">
                                    <label class="form-label" for="address_1">Adresse</label>
                                    <input class="form-control" type="text" name="address_1" id="address_1" value="<?=(esc($gym['address_1']) ?? '' ) ;?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="address_2">Complément d'adresse <span class="fst-italic">(facultatif)</span></label>
                                    <input class="form-control" type="text" name="address_2" id="address_2" value="<?=(esc($gym['address_2']) ?? '' ) ;?>">
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

                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="gps_location">Coordonnées GPS</label>
                                    <input class="form-control" type="text" name="gps_location" id="gps_location" value="<?=(esc($gym['gps_location']) ?? '' ) ;?>">
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
                                <div class="card-body">

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
                                <div class="card-body">

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

        //Initialisation du select des codes postaux

        //Initialisation du select des villes
        initAjaxSelect2(`#select-city`, {url:'/admin/city/search',searchFields:'label,zip_code',additionalFields:['department_number','department_name'],placeholder:'Rechercher une ville'});

    });
</script>
<style>
  .location-map {
      min-height:328px;
      background-color: yellow;
  }

  .select2-result-item__additionalFields {
      font-size: 0.85em;
      font-style: italic;
  }
</style>
<?php $this->endSection() ; ?>