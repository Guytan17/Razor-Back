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

    <!-- START : ZONE INDEX DES GYMNASES -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header hstack text-center">
                    <div class="card-title h3">Listes des gymnases</div>
                    <a href="" class="btn btn-sm btn-primary ms-auto p-1 mx-1">
                        <i class="fas fa-file-circle-plus"></i> Importer un fichier CSV
                    </a>
                    <a href="<?= base_url('/admin/gym/form')?>" class="btn btn-sm btn-primary p-1 mx-1">
                        <i class="fas fa-plus"></i> Créer un gymnase
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-striped" id="gymsTable">
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Code FBI</th>
                            <th>Nom</th>
                            <th>Ville</th>
                            <!--                        <th>Club(s)</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé en Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END : ZONE INDEX DES GYMNASES -->
    </div>
    <script>
        var baseUrl = "<?= base_url();?>" ;

        $(document).ready(function() {
            table = $('#gymsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: baseUrl + 'datatable/searchdatatable',
                    type: 'POST',
                    data: {
                        model: 'GymModel'
                    },
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
                                <a href="${baseUrl}/admin/gym/form/${row.id}" class="btn btn-sm btn-warning btn-edit-gym" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete-gym" title="Supprimer" data-id="${row.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `
                                ;
                        }
                    },
                    {data: 'id'},
                    {data: 'fbi_code'},
                    {data: 'name'},
                    {data: 'gym_city'}
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

            //Fonction pour appeler la fonction de suppression
            $(document).on('click', '.btn-delete-gym', function () {
                deleteGym($(this).data('id'));
            });

            //fonction de suppression d'un gymnase
            function deleteGym(id) {
                Swal.fire({
                    title: `Êtes-vous sûr ?`,
                    text: `Voulez-vous vraiment supprimer ce gymnase ?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: `Oui !`,
                    cancelButtonText: "Annuler",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= base_url('/admin/gym/delete/') ?>' + id,
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
                                    refreshTable();
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
        });

    </script>
    <?php $this->endSection() ?>
