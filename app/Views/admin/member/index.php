<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

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
    <!-- START : ZONE INDEX DES MEMBRES -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header hstack text-center">
                    <div class="card-title h3">Listes des membres du club</div>
                        <a href="" class="btn btn-sm btn-primary ms-auto p-1 mx-1">
                            <i class="fas fa-file-circle-plus"></i> Importer un fichier CSV
                        </a>
                        <a href="<?= base_url('/admin/member/form')?>" class="btn btn-sm btn-primary p-1 mx-1">
                            <i class="fas fa-user-plus"></i> Créer un membre
                        </a>
                    </div>
                </div>
            <div class="card-body">
                <table class="table table-sm table-striped" id="membersTable">
                    <thead>
                    <tr>
                        <th>Actions</th>
                        <th>ID membre</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôle</th>
                        <th>Numéro de licence</th>
                        <th>Code licence</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Chargé en Ajax -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- END : ZONE INDEX DES MEMBRES -->
</div>
<script>
    var baseUrl = "<?= base_url();?>"

    $(document).ready(function() {
        table = $('#membersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'MemberModel'
                },
            },
            columns: [{
                data: null,
                defaultContent: '',
                orderable: false,
                width: '150px',
                render: function (data, type, row) {
                    const isActive = row.deleted_at === null;
                    const toggleButton = isActive
                        ?
                        `
                                    <button
                                        class="btn btn-sm btn-success btn-toggleActive-member"
                                        title="Désactiver"
                                        data-id="${row.id}">
                                            <i class="fas fa-toggle-on"></i>
                                    </button>
                                `
                        :
                        `
                                <button
                                        class="btn btn-sm btn-danger btn-toggleActive-member"
                                        title="Activer"
                                        data-id="${row.id}">
                                            <i class="fas fa-toggle-off"></i>
                                    </button>
                                `
                    return `
                                <div class="btn-group" role="group">
                                    <button
                                        class="btn btn-sm btn-warning btn-edit-member"
                                        title="Modifier"
                                        data-id='${row.id}'
                                        data-last_name="${escapeHtml(row.last_name)}"
                                        data-first_name='${escapeHtml(row.first_name)}'
                                        data-id-role='${row.id_role}'
                                        data-license_number='${escapeHtml(row.license_number)}'
                                        data-license_code = '${escapeHtml(row.license_code)}'>
                                            <i class="fas fa-edit"></i>
                                    </button>
                                   ${toggleButton}
                                </div>
                            `
                        ;
                    }
                },
                {data:'id'},
                {data:'last_name'},
                {data:'first_name'},
                {data:'role_name'},
                {data:'license_number'},
                {data:'license_code'},
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

<?= $this->endSection() ?>
