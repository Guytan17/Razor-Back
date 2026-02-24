<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <span class="card-title h3">Création d'un gymnase</span>
                </div>
                <div class="card-body">
                    <!-- START : INFOS DU GYMNASE -->
                    <div class="row">
                        <!-- START: INPUTS GYMNASE -->
                        <div class="col-md-6 mb-3">
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="name">Nom du Gymnase</label>
                                    <input class="form-control" type="text" name="name" id="name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="fbi_number">Code FBI</label>
                                    <input class="form-control" type="text" name="fbi_number" id="fbi_number">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="address_1">Adresse</label>
                                    <input class="form-control" type="text" name="address_1" id="address_1">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="address_2">Complément d'adresse <span class="fst-italic">(facultatif)</span></label>
                                    <input class="form-control" type="text" name="address_2" id="address_2">
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
                                    <input class="form-control" type="text" name="gps_location" id="gps_location">
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
            </div>
        </div>
    </div>
</div>
<script>

</script>
<style>
  .location-map {
      min-height:241px;
      background-color: yellow;
  }
</style>
<?php $this->endSection() ; ?>