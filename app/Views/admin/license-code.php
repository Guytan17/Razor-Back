<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ;?>

<div  class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <!-- START : ZONE CREATION -->
            <div class="card">
                <?= form_open('/admin/license-code/insert') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un nouveau code licence</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="code">Code <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="code" id="code" value="<?=old('code')?>" required>
                    <div class="row mt-2">
                        <div class="col">
                            <label class="form-label" for="explanation">Explication du code <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="explanation" id="explanation" value="<?=old('explanation')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le code licence</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION -->
        </div>
        <div class="col-md-8">
            <!-- START : ZONE INDEX -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des codes licence</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="LicenceCodesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Explication du code</th>
                            <!-- <th>Nombre de licences ayant ce code</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX-->
        </div>
    </div>
</div>
<script>
    var baseUrl = "<?=base_url();?>";

    $(document).ready(function() {
        table = $('#LicenceCodesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'LicenseCodeModel'
                }
            },
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    orderable: false,
                    width: '150px',
                    render: function (data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <button
                                    class="btn btn-sm btn-warning btn-edit-licence-code"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-code='${escapeHtml(row.code)}'
                                    data-explanation='${escapeHtml(row.explanation)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-licence-code"
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
        });

        // Fonction pour actualiser la table
        window.refreshTable = function () {
            table.ajax.reload(null, false); // false pour garder la pagination
        };

    });
</script>
<?php $this->endSection() ;?>
