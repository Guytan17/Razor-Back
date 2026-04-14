<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<div class="container-fluid">
    <!-- START : ZONE INDEX DES MATCHS -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header hstack text-center">
                    <div class="card-title h3">Listes des matchs</div>
                    <a href="" class="btn btn-sm btn-primary ms-auto p-1 mx-1">
                        <i class="fas fa-file-circle-plus"></i> Importer un fichier CSV
                    </a>
                    <a href="<?= base_url('/admin/game/form')?>" class="btn btn-sm btn-primary p-1 mx-1">
                        <i class="fas fa-plus"></i> Créer un match
                    </a>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-sm table-striped" id="gamesTable">
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Numéro FBI</th>
                            <th>Catégorie</th>
                            <th>Championnat</th>
                            <th>Équipe de Tasdon</th>
                            <th>Adversaire</th>
                            <th>Horaire</th>
                            <th>Lieu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé en Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END : ZONE INDEX DES MATCHS -->
    </div>
</div>
<script>
    var baseUrl = "<?= base_url();?>" ;

    $(document).ready(function() {
        table = $('#gamesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'GameModel'
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
                                    class="btn btn-sm btn-success btn-toggleActive-game"
                                    title="Désactiver"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-on"></i>
                                </button>
                            `
                        :
                        `
                            <button
                                    class="btn btn-sm btn-danger btn-toggleActive-game"
                                    title="Activer"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-off"></i>
                                </button>
                            `
                    return `
                            <div class="btn-group" role="group">
                                <a  href="${baseUrl}/admin/game/form/${row.id}" class="btn btn-sm btn-warning btn-edit-club" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                               ${toggleButton}
                            </div>
                        `
                        ;
                }
            },
                {data : 'id'},
                {data : 'fbi_number'},
                {data : 'category'},
                {data : 'division'},
                {data : 'team'},
                {data: 'opponent'},
                {data : 'schedule'},
                {
                    data : 'place',
                    className: 'minSize',
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
    $(document).on('click','.btn-toggleActive-game', function(){
        toggleActive($(this).data('id'));
    });

    function toggleActive(gameId) {
        // Effectuer la requête AJAX
        $.ajax({
            url: '<?= base_url('admin/game/switch-active/') ?>' + gameId,
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
                    $('#gamesTable').DataTable().ajax.reload(null, false);

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
    .minSize {
        min-width: 100px;
    }
</style>
<?php $this->endSection(); ?>
