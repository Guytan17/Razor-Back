<?php $this->extend('/layouts/admin'); ?>

<?php $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- START : ZONE CRÉATION -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open_multipart('admin/technical-foul/insert') ?>
                <div class="card-header">
                    <span class="card-title h5">Création d'une faute technique</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label" for="type_tf">Type</label>
                            <div class="input-group">
                                <select class="form-select" name="id_type" id="type_tf"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="classification_tf">Classification</label>
                            <div class="input-group">
                                <select class="form-select" name="id_classification" id="classification_tf"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="game_tf">Match</label>
                            <div class="input-group">
                                <select class="form-select" name="id_game" id="game_tf"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="member_tf">Joueur</label>
                            <div class="input-group">
                                <select class="form-select" name="id_member" id="member_tf"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="amount">Montant</label>
                            <div class="input-group">
                                <input class="form-control" type="number" name="amount" id="amount" value="<?= esc($technical_foul['amount'] ?? '') ?>">
                                <span class="input-group-text text-decoration">€</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la faute Technique</button>
                </div>
                <?= form_close() ; ?>
            </div>
        </div>
        <!-- END : ZONE CRÉATION -->
        <!-- START : ZONE INDEX -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des fautes techniques</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="technicalFoulsTable">
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Joueur/coach</th>
                            <th>Type</th>
                            <th>Classification</th>
                            <th>Match</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- chargé en Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END : ZONE INDEX -->
    </div>
    <!-- START : MODAL POUR LES MODIFICATIONS -->
    <div class="modal" id="modalTechnicalFoul" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la faute technique </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button onclick="saveTechnicalFoul()" type="button" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END : MODAL POUR LES MODIFICATIONS -->
</div>
<script>
    var baseUrl = "<?=base_url();?>";

    $(document).ready(function() {

        //INITIALISATION DES SELECT2 (CRÉATION)
        //Select2 types
        initAjaxSelect2(`#type_tf`, {url:'/admin/technical-foul-params/search-type', searchFields: 'code',additionalFields:'explanation', placeholder:'Choisir le type de faute technique'});

        //Select2 classifications
        initAjaxSelect2(`#classification_tf`, {url:'/admin/technical-foul-params/search-classification', searchFields: 'code',additionalFields:'explanation', placeholder:'Choisir la classification de faute technique'});

        //Select2 classifications
        initAjaxSelect2(`#game_tf`, {url:'/admin/game/search', searchFields: 'fbi_number',additionalFields:'schedule', placeholder:'Choisir le match'});

        //Select2 des joueurs
        initAjaxSelect2(`#member_tf`, {url:'/admin/member/search', searchFields: 'first_name,last_name', placeholder:'Choisir le membre'});
    });
</script>
<?php $this->endSection();