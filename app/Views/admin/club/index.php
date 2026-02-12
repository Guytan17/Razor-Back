<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ?>

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

    <!-- START : ZONE INDEX DES CLUBS -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header hstack text-center">
                    <div class="card-title h3">Listes des clubs</div>
                    <a href="" class="btn btn-sm btn-primary ms-auto p-1 mx-1">
                        <i class="fas fa-file-circle-plus"></i> Importer un fichier CSV
                    </a>
                    <a href="<?= base_url('/admin/club/form')?>" class="btn btn-sm btn-primary p-1 mx-1">
                        <i class="fas fa-user-plus"></i> Créer un club
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-striped" id="clubsTable">
                    <thead>
                    <tr>
                        <th>Actions</th>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Code FBI</th>
                        <th>Couleurs</th>
<!--                        <th>Nombre d'équipes</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Chargé en Ajax -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END : ZONE INDEX DES CLUBS -->
</div>
<script>
    var baseUrl = "<?= base_url();?>" ;

    $(document).ready(function() {
        table = $('#clubsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'ClubModel'
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
                                    class="btn btn-sm btn-success btn-toggleActive-club"
                                    title="Désactiver"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-on"></i>
                                </button>
                            `
                        :
                        `
                            <button
                                    class="btn btn-sm btn-danger btn-toggleActive-club"
                                    title="Activer"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-off"></i>
                                </button>
                            `
                    return `
                            <div class="btn-group" role="group">
                                <a  href="${baseUrl}/admin/club/form/${row.id}" class="btn btn-sm btn-warning btn-edit-club" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                               ${toggleButton}
                            </div>
                        `
                        ;
                    }
                },
                {data : 'id'},
                {data : 'name'},
                {data: 'code'},
                {data : 'colors'},

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

    //Fonction pour appeler la fonction de désactivation/activation
    $(document).on('click','.btn-toggleActive-club', function(){
        toggleActive($(this).data('id'));
    });

    function toggleActive(clubId) {
        // Effectuer la requête AJAX
        $.ajax({
            url: '<?= base_url('admin/club/switch-active/') ?>' + clubId,
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
                    // Recharger le DataTable pour voir le changement
                    $('#clubsTable').DataTable().ajax.reload(null, false);

                    // Notification toast
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur !',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: 'Erreur !',
                    text: 'Une erreur est survenue.',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
</script>
<?php $this->endSection() ?>
