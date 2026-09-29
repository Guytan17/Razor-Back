<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<div class="container-fluid">
    <!-- START : ZONE POUR LES ALERTES BOOTSTRAP -->
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
    <!-- END : ZONE POUR LES ALERTES BOOTSTRAP -->

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex">
                    <span class="card-title h5">Liste des sponsors</span>
                    <a href="<?= base_url('/admin/sponsor/form')?>" class="btn btn-sm btn-primary ms-auto p-1 mx-1">
                        <i class="fas fa-plus"></i> Créer un sponsor
                    </a>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="sponsorsTable">
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Nom du sponsor</th>
                            <th>Niveau d'importance</th>
                            <th>Saison(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- chargé en Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var baseUrl = "<?=base_url();?>";

    $(document).ready(function() {
        // Initialiser l'aperçu du logo
        initImagePreview('#logo', '#logoPreview', '<?= esc(base_url('/assets/img/default.png'), 'js') ?>', 2);

        table = $('#sponsorsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'SponsorModel'
                }
            },
            columns: [
                {
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
                                        class="btn btn-sm btn-success btn-toggleActive-sponsor"
                                        title="Désactiver"
                                        data-id="${row.id}">
                                            <i class="fas fa-toggle-on"></i>
                                    </button>
                                `
                            :
                            `
                                <button
                                        class="btn btn-sm btn-danger btn-toggleActive-sponsor"
                                        title="Activer"
                                        data-id="${row.id}">
                                            <i class="fas fa-toggle-off"></i>
                                    </button>
                                `
                        return `
                                <div class="btn-group" role="group">
                                    <a  href="${baseUrl}/admin/sponsor/form/${row.id}" class="btn btn-sm btn-warning btn-edit-sponsor" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                   ${toggleButton}
                                </div>
                            `
                            ;
                    }
                },
                {data: 'id'},
                {
                    className: 'dt-center',
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.logo_url) {
                            return `<img style="height:40px;" src='${baseUrl}/${row.logo_url}'>
                                    `;
                        } else {
                            return `<img style="height:40px;" src='${baseUrl}/assets/img/default.png'>
                                    `;
                        }
                    }
                },
                {
                    data: 'sponsor_name',
                    name: 'sponsor.name'
                },
                {
                    className: 'dt-left',
                    data: 'rank_label'
                },
                {
                    data: 'seasons_name',
                    name: 'season.name'
                },
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
    $(document).on('click','.btn-toggleActive-sponsor', function(){
        toggleActive($(this).data('id'));
    })

    function toggleActive(sponsorId) {
        // Effectuer la requête AJAX
        $.ajax({
            url: '<?= base_url('admin/sponsor/switch-active/') ?>' + sponsorId,
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
                    $('#sponsorsTable').DataTable().ajax.reload(null, false);

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

<style>

</style>

<?php $this->endsection() ; ?>