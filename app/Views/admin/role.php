<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <!-- START : ZONE CREATION -->
            <div class="card">
                <?= form_open('/admin/role/insert') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un nouveau rôle</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="name">Nom du rôle <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="name" id="name" value="<?=old('name')?>" required>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le rôle</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION -->
        </div>
        <div class="col-md-8">
            <!-- START : ZONE INDEX -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des rôles </span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="rolesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Nom du rôle</th>
                            <!-- <th>Nombre de membres ayant ce rôle</th>-->
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
    <!-- START : MODAL POUR LES MODIFICATIONS -->
    <div class="modal" id="modalLeague" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le championnat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="modalNameInput">Nom du championnat</label>
                    <input class="form-control" id="modalNameInput" type="text">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button onclick="saveLeague()" type="button" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END : MODAL POUR LES MODIFICATIONS -->
</div>
<script>
    var baseUrl = "<?=base_url();?>";
    var table;

    $(document).ready(function() {
        table = $('#rolesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'RoleModel'
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
                                    class="btn btn-sm btn-warning btn-edit-role"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-name='${escapeHtml(row.name)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-role"
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
                {data: 'name'},
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

    //Définition de la modal
    const myModal = new bootstrap.Modal('#modalRole');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-role', function() {
        const btn = $(this);

        $('#modalNameInput').val(btn.data('name'));
        $('#modalNameInput').data('id',btn.data('id'));

        myModal.show();
    });

    //Fonction pour appeler la fonction de suppression
    $(document).on('click','.btn-delete-role', function(){
        deleteRole($(this).data('id'));
    })

    function saveRole () {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        $.ajax({
            url: baseUrl + 'admin/role/update/'+id,
            type:'POST',
            data: {
                name: name
            },
            success: function(response) {
                myModal.hide();
                if(response.success){
                    Swal.fire({
                        title : 'Succès !',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    //Actualiser la table
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

    function deleteRole(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer ce rôle ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/role/delete/') ?>'+id,
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
</script>
<style>

</style>
<?= $this->endSection() ?>
