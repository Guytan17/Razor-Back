<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open('/admin/category/insert') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'une nouvelle catégorie</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="name">Nom de la catégorie<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="gender">Genre de la catégorie</label>
                            <select class="form-select" name="gender" id="gender">
                                <option value="mixed">Mixte</option>
                                <option value="man">Masculin</option>
                                <option value="woman">Féminin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la catégorie</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des catégories</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="categoriesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Nom de la catégorie</th>
                            <th>Genre de la catégorie</th>
                            <!-- <th>Nombre de membres dans cette catégorie</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- START : MODAL POUR LES MODIFICATIONS -->
    <div class="modal" id="modalCategory" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la category </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="modalNameInput">Nom de la catégorie</label>
                            <input class="form-control" id="modalNameInput" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="modalGenderSelect">Genre de la catégorie</label>
                            <select class="form-select" name="modalGenderSelect" id="modalGenderSelect">
                                <option value="mixed">Mixte</option>
                                <option value="man">Masculin</option>
                                <option value="woman">Féminin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button onclick="saveCategory()" type="button" class="btn btn-primary">Sauvegarder</button>
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
        table = $('#categoriesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'CategoryModel'
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
                                <button onclick="showModal(${row.id},'${row.name}','${row.gender}')" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteCategory(${row.id})" class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `
                            ;
                    }
                },
                {data: 'id'},
                {data: 'name'},
                {data: 'gender'}
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

    const myModal = new bootstrap.Modal('#modalCategory');

    function showModal(id,name,gender){
        $('#modalNameInput').val(name);
        $('#modalNameInput').data('id',id);
        $('#modalGenderSelect').val(gender);
        myModal.show();
    }

    function saveCategory () {
        let name = $('#modalNameInput').val();
        let gender = $('#modalGenderSelect').val();
        let id = $('#modalNameInput').data('id');
        $.ajax({
            url: baseUrl + 'admin/category/update/'+id,
            type:'POST',
            data: {
                name: name,
                gender: gender
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

    function deleteCategory(id){
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cette catégorie ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/category/delete/') ?>'+id,
                    type: 'POST',
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

<?= $this->endSection() ?>
