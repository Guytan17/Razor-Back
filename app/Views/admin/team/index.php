<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<div class="container-fluid">
    <!-- START : ZONE POUR LES TOASTS -->
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
    <!-- END : ZONE POUR LES TOASTS -->
    <!-- START : ZONE POUR LES CLUBS -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header hstack text-center">
                    <div class="card-title h3">Listes des équipes du club</div>
                    <a href="" class="btn btn-sm btn-primary ms-auto p-1 mx-1">
                        <i class="fas fa-file-circle-plus"></i> Importer un fichier CSV
                    </a>
                    <a href="<?= base_url('/admin/team/form')?>" class="btn btn-sm btn-primary p-1 mx-1">
                        <i class="fas fa-plus"></i> Créer une équipe
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-striped" id="teamsTable">
                        <thead>
                        <tr>
                            <th>Action</th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Saison</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END : ZONE POUR LES CLUBS -->
</div>
<script>
    var baseUrl = "<?= base_url();?>" ;

    $(document).ready(function() {
        table = $('#teamsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'TeamModel'
                },
            },
            columns: [{
                data: null,
                defaultContent: '',
                orderable: false,
                width: '100px',
                render: function (data, type, row) {
                    const isActive = row.deleted_at === null;
                    const toggleButton = isActive
                        ?
                        `
                                    <button
                                        class="btn btn-sm btn-success btn-toggleActive-team"
                                        title="Désactiver"
                                        data-id="${row.id}">
                                            <i class="fas fa-toggle-on"></i>
                                    </button>
                                `
                        :
                        `
                                <button
                                        class="btn btn-sm btn-danger btn-toggleActive-team"
                                        title="Activer"
                                        data-id="${row.id}">
                                            <i class="fas fa-toggle-off"></i>
                                    </button>
                                `
                    return `
                                <div class="btn-group" role="group">
                                    <a  href="${baseUrl}/admin/team/form/${row.id}" class="btn btn-sm btn-warning btn-edit-member" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                   ${toggleButton}
                                </div>
                            `
                        ;
                }
            },
                {data:'id'},
                {data:'name'},
                {data:'category_name'},
                {data:'season_name'},
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
<?php $this->endSection() ; ?>