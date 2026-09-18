<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ;?>

<div class="container-fluid">
    <div class="row d-flex">
    <!-- START : ZONE RANK-SPONSOR -->
        <div class="col-md-6 mb-3">
            <!-- START : ZONE CREATION RANK -->
            <div class="card mb-3">
                <?= form_open('/admin/sponsors-params/insert-rank') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un rang d'importance des sponsors</span>
                </div>
                <div class="card-body card-body-rank">
                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label" for="rank">Rang<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="rank" id="rank" value="<?= old('rank')?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="label">Label du rang <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="label" id="label" value="<?=old('label')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le rang d'importance des sponsors</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION RANK -->
            <!-- START : ZONE INDEX RANK -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des rangs d'importance des sponsors</span>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="ranksTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Rang</th>
                            <th>Libellé</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX RANK -->
        </div>
        <!-- START : MODAL POUR LES MODIFICATIONS -->
        <div class="modal" id="modalRank" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier le rang d'importance des sponsors</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col">
                                <label class="form-label" for="modalRankInput">Rang</label>
                                <input class="form-control" id="modalRankInput" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="modalRankLabelInput">Label <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="modalRankLabelInput" id="modalRankLabelInput" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button onclick="saveRank()" type="button" class="btn btn-primary">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END : MODAL POUR LES MODIFICATIONS -->
    <!-- END : ZONE RANK-SPONSOR -->

    <!-- START : ZONE DOTATION-TYPE -->
        <div class="col-md-6 mb-3">
            <!-- START : ZONE CREATION TYPE -->
            <div class="card mb-3">
                <?= form_open('/admin/sponsors-params/insert-type') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un type dotation</span>
                </div>
                <div class="card-body card-body-type">
                    <div class="row mb-2 w-100 g-0">
                        <div class="col">
                            <label class="form-label" for="dotation-type">Type de dotation<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="dotation_type" id="dotation-type" value="<?= old('dotation-type')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le type de faute technique</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION TYPE -->
            <!-- START : ZONE INDEX TYPES DE DOTATION -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des types de dotation</span>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="typesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Types</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX TYPES DE DOTATION -->
        </div>
        <!-- START : MODAL POUR LES MODIFICATIONS -->
        <div class="modal" id="modalType" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier le type de dotation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col">
                                <label class="form-label" for="modalTypeInput">Type</label>
                                <input class="form-control" id="modalTypeInput" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button onclick="saveType()" type="button" class="btn btn-primary">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END : MODAL POUR LES MODIFICATIONS -->
    <!-- END : ZONE DOTATION-TYPE -->
    </div>
</div>

<script>
    var baseUrl = "<?=base_url();?>";
    let tableRank;
    let tableType;

    $(document).ready(function() {
        //GESTION INDEX DES RANGS
        tableRank = $('#ranksTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'SponsorRankModel'
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
                                    class="btn btn-sm btn-warning btn-edit-rank"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-rank='${escapeHtml(row.rank)}'
                                    data-label='${escapeHtml(row.label)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-rank"
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
                {data: 'rank'},
                {data: 'label'},
            ],
            language: {
                url: baseUrl + 'assets/js/datatable/datatable-2.3.5-fr-FR.json',
            },
            order: [[1, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        })

        // Fonction pour actualiser la table des rangs
        window.refreshTableRank = function () {
            tableRank.ajax.reload(null, false); // false pour garder la pagination
        }
        //GESTION INDEX DES TYPES DE DOTATION
        tableType = $('#typesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'DotationTypeModel'
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
                                    class="btn btn-sm btn-warning btn-edit-type"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-type='${escapeHtml(row.type)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-type"
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
                {data: 'type'},
            ],
            language: {
                url: baseUrl + 'assets/js/datatable/datatable-2.3.5-fr-FR.json',
            },
            order: [[1, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        })
        // Fonction pour actualiser la table des types
        window.refreshTableType = function () {
            tableType.ajax.reload(null, false); // false pour garder la pagination
        }
    });

    //FONCTIONS POUR APPELER LES FONCTIONS DE SUPPRESSION
    //Pour RANK
    $(document).on('click','.btn-delete-rank', function(){
        deleteRank($(this).data('id'));
    })
    //Pour TYPE
    $(document).on('click','.btn-delete-type', function(){
        deleteType($(this).data('id'));
    })

    //MODAL RANK
    //Définition de la modal Rank
    const myModalRank = new bootstrap.Modal('#modalRank');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-rank', function() {
        const btn = $(this);

        $('#modalRankInput').val(btn.data('rank'));
        $('#modalRankInput').data('id',btn.data('id'));
        $('#modalRankLabelInput').val(btn.data('label'));

        myModalRank.show();
    });

    //MODAL TYPE
    //Définition de la modal Type
    const myModalType = new bootstrap.Modal('#modalType');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-type', function() {
        const btn = $(this);

        $('#modalTypeInput').val(btn.data('type'));
        $('#modalTypeInput').data('id',btn.data('id'));

        myModalType.show();
    });

    // FONCTIONS SAVE POUR RANK ET TYPE
    function saveRank () {
        let rank = $('#modalRankInput').val();
        let id = $('#modalRankInput').data('id');
        let label = $('#modalRankLabelInput').val();
        $.ajax({
            url: baseUrl + 'admin/sponsors-params/update-rank/'+id,
            type:'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                rank: rank,
                label: label,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                if(response.success){
                    myModalRank.hide();
                    Swal.fire({
                        title : 'Succès !',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    //Actualiser la table
                    refreshTableRank();
                } else {
                    Swal.fire({
                        title: 'Erreur !',
                        html: getAjaxErrorMessage(response),
                        icon: 'error'
                    });
                }
            }
        })
    }

    function saveType () {
        let type = $('#modalTypeInput').val();
        let id = $('#modalTypeInput').data('id');
        $.ajax({
            url: baseUrl + 'admin/sponsors-params/update-type/'+id,
            type:'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                type: type,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                if(response.success){
                    myModalType.hide();
                    Swal.fire({
                        title : 'Succès !',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    //Actualiser la table
                    refreshTableType();
                } else {
                    Swal.fire({
                        title: 'Erreur !',
                        html: getAjaxErrorMessage(response),
                        icon: 'error'
                    });
                }
            }
        })
    }

    //FONCTIONS DE SUPPRESSION
    //RANK
    function deleteRank(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer ce rang ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/sponsors-params/delete-rank/') ?>' + id,
                    type: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            refreshTableRank();
                        } else {
                            Swal.fire({
                                title: 'Erreur !',
                                text: 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    }
                })
            }
        });
    }

    //TYPE
    function deleteType(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer ce type de dotation ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/sponsors-params/delete-type/') ?>'+id,
                    type: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            refreshTableType();
                        } else {
                            Swal.fire({
                                title: 'Erreur !',
                                text: 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    }
                })
            }
        });
    }

</script>

<style>
    /* fonction pour que la taille des card de création soient égales sauf en affichage mobile   */
    @media (min-width: 768px) {
        .card-body-rank, .card-body-type {
            height: 12rem;
        }

        .card-body-type {
            display: flex;
            align-items: center;
    }
</style>

<?php $this->endSection() ?>
