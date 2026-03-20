<?php $this->extend('/layouts/admin'); ?>

<?php $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- START : ZONE CRÉATION -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open('admin/technical-foul/insert') ?>
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
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="technicalFoulsTable">
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Joueur/coach</th>
                            <th>Type</th>
                            <th>Classification</th>
                            <th>Match</th>
                            <th>Montant</th>
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
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label" for="modal_type_tf">Type</label>
                            <div class="input-group">
                                <select class="form-select" name="id_type_modal" id="modal_type_tf"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="modal_classification_tf">Classification</label>
                            <div class="input-group">
                                <select class="form-select" name="id_classification_modal" id="modal_classification_tf"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="modal_game_tf">Match</label>
                            <div class="input-group">
                                <select class="form-select" name="id_game_modal" id="modal_game_tf"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="modal_member_tf">Joueur</label>
                            <div class="input-group">
                                <select class="form-select" name="id_member_modal" id="modal_member_tf"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="modal_amount">Montant</label>
                            <div class="input-group">
                                <input class="form-control" type="number" name="amount_modal" id="modal_amount">
                                <span class="input-group-text text-decoration">€</span>
                            </div>
                        </div>
                    </div>
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
        let team;

        //ZONE CRÉATION
        //INITIALISATION DES SELECT2 (CRÉATION)
        //Select2 types
        initAjaxSelect2(`#type_tf`, {url:'/admin/technical-foul-params/search-type', searchFields: 'code',additionalFields:'explanation', placeholder:'Choisir le type de faute technique'});

        //Select2 classifications
        initAjaxSelect2(`#classification_tf`, {url:'/admin/technical-foul-params/search-classification', searchFields: 'code',additionalFields:'explanation', placeholder:'Choisir la classification de faute technique'});

        //Select2 match
        initAjaxSelect2(`#game_tf`, {url:'/admin/game/search', searchFields:'fbi_number',additionalFields:'schedule,category', placeholder:'Choisir le match'});

        //Select2 des joueurs
        initAjaxSelect2(`#member_tf`, {url:'/admin/member/search', searchFields: 'first_name,last_name', placeholder:'Choisir le membre'});

        //Restriction du choix des joueurs à l'équipe ayant disputé le match si un match est sélectionné
        $('#game_tf').on('select2:select', function(){
           let selectedGame = $(this).select2('data');

           if(selectedGame[0].home_club == 1){
               team = selectedGame[0].home_team;
           } else if (selectedGame[0].away_club == 1){
               team = selectedGame[0].away_team;
           }

           console.log(team);
            //Select2 des joueurs avec filtre de l'équipe
            initAjaxSelect2(`#member_tf`, {url:'/admin/player/search', searchFields: 'first_name,last_name', placeholder:'Choisir le membre',extraParams:{id_team:team}});

        });

        //Réinitialisation du select avec tous les joueurs si la selection du match est retirée
        $('#game_tf').on('select2:unselect', function(){
            //Select2 des joueurs
            initAjaxSelect2(`#member_tf`, {url:'/admin/member/search', searchFields: 'first_name,last_name', placeholder:'Choisir le membre'});
        })

        //ZONE INDEX
        table = $('#technicalFoulsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'TechnicalFoulModel'
                }
            },
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    orderable: false,
                    width: '100px',
                    render: function (data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <button
                                    class="btn btn-sm btn-warning btn-edit-technical-foul"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-game-id='${escapeHtml(row.id_game)}'
                                    data-game-fbi-number='${escapeHtml(row.game_fbi_number)}'
                                    data-member-id='${escapeHtml(row.id_member)}'
                                    data-member-name='${escapeHtml(row.member_name)}'
                                    data-type-id='${escapeHtml(row.id_type)}'
                                    data-type-code='${escapeHtml(row.type)}'
                                    data-classification-id='${escapeHtml(row.id_classification)}'
                                    data-classification-code='${escapeHtml(row.classification)}'
                                    data-amount='${escapeHtml(row.amount)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-technical-foul"
                                    title="Supprimer"
                                    data-id="${row.id}">
                                        <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `
                            ;
                    }
                },
                {data: 'id'},
                {data: 'member_name'},
                {data: 'type'},
                {data: 'classification'},
                {data: 'game_fbi_number'},
                {data: 'amount'},
            ],
            language: {
                url: baseUrl + 'assets/js/datatable/datatable-2.3.5-fr-FR.json',
            },
            order: [[1, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        });

        // Fonction pour actualiser la table
        window.refreshTable = function () {
            table.ajax.reload(null, false); // false pour garder la pagination
        };

        //Définition de la modal
        const myModal = new bootstrap.Modal('#modalTechnicalFoul');

        //Fonction pour ouvrir la modal avec les données préremplies
        $(document).on('click','.btn-edit-technical-foul', function() {

            const btn = $(this);

            $('#modalTechnicalFoul').data('editData',{
                gameId: btn.data('game-id'),
                gameFbiNumber: btn.data('game-fbi-number'),
                memberId: btn.data('member-id'),
                memberName: btn.data('member-name'),
                typeId: btn.data('type-id'),
                typeCode: btn.data('typeCode'),
                classificationId: btn.data('classification-id'),
                classificationCode: btn.data('classification-code'),
                amount: btn.data('amount')
            })

            myModal.show();
        });

        //Fonction pour remplir la modal avec les données d'édition
        $('#modalTechnicalFoul').on('shown.bs.modal', function() {
            const data = $(this).data('editData');



            //INITIALISATION DES SELECT2 (MODIFICATION)
            //Select2 types
            initAjaxSelect2(`#modal_type_tf`, {dropdownParent:$(this),url:'/admin/technical-foul-params/search-type', searchFields: 'code',additionalFields:'explanation', placeholder:'Choisir le ' +
                    'type de faute technique',
                });

            //Select2 classifications
            initAjaxSelect2(`#modal_classification_tf`, {dropdownParent:$(this),url:'/admin/technical-foul-params/search-classification', searchFields: 'code',additionalFields:'explanation',
                placeholder:'Choisir la classification de faute technique'});

            //Select2 match
            initAjaxSelect2(`#modal_game_tf`, {dropdownParent:$(this),url:'/admin/game/search', searchFields:'fbi_number',additionalFields:'schedule,category', placeholder:'Choisir le match'});

            //Select2 des joueurs
            initAjaxSelect2(`#modal_member_tf`, {dropdownParent:$(this),url:'/admin/member/search', searchFields: 'first_name,last_name', placeholder:'Choisir le membre',extraParams:{teamId:team}});

            //On préremplit les champs avec les valeurs existantes
            let optionType = new Option(data.typeCode, data.typeId,true,true);
            $('#modal_type_tf').append(optionType);

            let optionClassification = new Option(data.classificationCode, data.classificationId,true,true);
            $('#modal_classification_tf').append(optionClassification);

            let optionGame = new Option(data.gameFbiNumber, data.gameId,true,true);
            $('#modal_game_tf').append(optionGame);

            let optionMember = new Option(data.memberName, data.memberId,true,true);
            $('#modal_member_tf').append(optionMember);

            $('#modal_amount').val(data.amount);
        });


        //Fonction pour appeler la fonction de suppression
        $(document).on('click','.btn-delete-technical-foul', function(){
            deleteTechnicalFoul($(this).data('id'));
        });
    });
</script>
<?php $this->endSection();