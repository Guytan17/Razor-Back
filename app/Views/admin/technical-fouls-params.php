<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ;?>

<div  class="container-fluid">
    <div class="row">
        <!-- START : ZONE TYPE -->
        <div class="col-md-6 mb-3">
            <!-- START : ZONE CREATION TYPE -->
            <div class="card mb-3">
                <?= form_open('/admin/technical-foul-params/insert-type') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un type de faute technique</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="code-type">Code du type<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="code_type" id="code-type" value="<?= old('code-type')?>" required>
                    <div class="row mt-2">
                        <div class="col">
                            <label class="form-label" for="explanation-type">Explication du code <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="explanation_type" id="explanation-type" value="<?=old('explanation-type')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le type de faute technique</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION TYPE -->
            <!-- START : ZONE INDEX TYPE -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des types de faute technique</span>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="typesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Explication du code</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX TYPE -->
            <!-- START : MODAL POUR LES MODIFICATIONS -->
            <div class="modal" id="modalType" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier le type de faute technique</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label" for="modalTypeCodeInput">Code</label>
                            <input class="form-control" id="modalTypeCodeInput" type="text">
                            <div class="row mt-2">
                                <div class="col">
                                    <label class="form-label" for="modalTypeExplanationInput">Explication du code <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="modalTypeExplanationInput" id="modalTypeExplanationInput" required>
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
        </div>
        <!-- END : ZONE TYPE -->
        <!-- START : ZONE CLASSIFICATION -->
        <div class="col-md-6 mb-3">
            <!-- START : ZONE CREATION CLASSIFICATION -->
            <div class="card mb-3">
                <?= form_open('/admin/technical-foul-params/insert-classification') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'une classification de faute technique</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="code-classification">Code de la classification<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="code_classification" id="code-classification" value="<?= esc(old('code-classification'))?>" required>
                    <div class="row mt-2">
                        <div class="col">
                            <label class="form-label" for="explanation-classification">Explication du code <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="explanation_classification" id="explanation-classification" value="<?=esc(old('explanation-classification'))?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la classification de faute technique</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION CLASSIFICATION -->
            <!-- START : ZONE INDEX CLASSIFICATION -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des classifications de faute technique</span>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="classificationsTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Explication du code</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX CLASSIFICATION -->
            <!-- START : MODAL POUR LES MODIFICATIONS -->
            <div class="modal" id="modalClassification" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier la classification de faute technique</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label" for="modalClassificationCodeInput">Code</label>
                            <input class="form-control" id="modalClassificationCodeInput" type="text">
                            <div class="row mt-2">
                                <div class="col">
                                    <label class="form-label" for="modalClassificationExplanationInput">Explication du code <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="modalClassificationExplanationInput" id="modalClassificationExplanationInput" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button onclick="saveClassification()" type="button" class="btn btn-primary">Sauvegarder</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END : MODAL POUR LES MODIFICATIONS -->
        </div>
        <!-- END : ZONE CLASSIFICATION-->
    </div>
</div>
<script>
    var baseUrl = "<?=base_url();?>";
    let tableType;
    let tableClassification;

    //GESTION INDEX DES TYPES
    $(document).ready(function() {
        tableType = $('#typesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'TypeModel'
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
                                    data-code='${escapeHtml(row.code)}'
                                    data-explanation='${escapeHtml(row.explanation)}'>
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
                {data: 'code'},
                {data: 'explanation'},
            ],
            language: {
                url: baseUrl + 'assets/js/datatable/datatable-2.3.5-fr-FR.json',
            },
            order: [[1, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        })

        // Fonction pour actualiser la table
        window.refreshTableType = function () {
            tableType.ajax.reload(null, false); // false pour garder la pagination
        }

        //GESTION INDEX DES CLASSIFICATIONS
        tableClassification = $('#classificationsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'ClassificationModel'
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
                                class="btn btn-sm btn-warning btn-edit-classification"
                                title="Modifier"
                                data-id='${row.id}'
                                data-code='${escapeHtml(row.code)}'
                                data-explanation='${escapeHtml(row.explanation)}'>
                                    <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete-classification"
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
                {data: 'code'},
                {data: 'explanation'},
            ],
            language: {
                url: baseUrl + 'assets/js/datatable/datatable-2.3.5-fr-FR.json',
            },
            order: [[1, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        })

        // Fonction pour actualiser la table
        window.refreshTableClassification = function () {
            tableClassification.ajax.reload(null, false); // false pour garder la pagination
        }
    });

    //MODAL TYPE
    //Définition de la modal Type
    const myModalType = new bootstrap.Modal('#modalType');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-type', function() {
        const btn = $(this);

        $('#modalTypeCodeInput').val(btn.data('code'));
        $('#modalTypeCodeInput').data('id',btn.data('id'));
        $('#modalTypeExplanationInput').val(btn.data('explanation'));

        myModalType.show();
    });

    //MODAL CLASSIFICATION
    //Définition de la modal Type
    const myModalClassification = new bootstrap.Modal('#modalClassification');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-classification', function() {
        const btn = $(this);

        $('#modalClassificationCodeInput').val(btn.data('code'));
        $('#modalClassificationCodeInput').data('id',btn.data('id'));
        $('#modalClassificationExplanationInput').val(btn.data('explanation'));

        myModalClassification.show();
    });

    //FONCTIONS POUR APPELER LES FONCTIONS DE SUPPRESSION
    //Pour TYPE
    $(document).on('click','.btn-delete-type', function(){
        deleteType($(this).data('id'));
    })
    //Pour CLASSIFICATION
    $(document).on('click','.btn-delete-classification', function(){
        deleteClassification($(this).data('id'));
    })

    // FONCTIONS SAVE POUR TYPE ET CLASSIFICATION
    function saveType () {
        let code = $('#modalTypeCodeInput').val();
        let id = $('#modalTypeCodeInput').data('id');
        let explanation = $('#modalTypeExplanationInput').val();
        $.ajax({
            url: baseUrl + 'admin/technical-foul-params/update-type/'+id,
            type:'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                codeType: code,
                explanationType: explanation,
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

    function saveClassification () {
        let code = $('#modalClassificationCodeInput').val();
        let id = $('#modalClassificationCodeInput').data('id');
        let explanation = $('#modalClassificationExplanationInput').val();
        $.ajax({
            url: baseUrl + 'admin/technical-foul-params/update-classification/'+id,
            type:'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                codeClassification: code,
                explanationClassification: explanation,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                if(response.success){
                    myModalClassification.hide();
                    Swal.fire({
                        title : 'Succès !',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    //Actualiser la table
                    refreshTableClassification();
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
    //Type
    function deleteType(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer ce type ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/technical-foul-params/delete-type/') ?>'+id,
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

    //Classification
    function deleteClassification(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cette classification ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/technical-foul-params/delete-classification/') ?>'+id,
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
                            refreshTableClassification();
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
<?php $this->endSection() ;?>
