<?php $this->extend('layouts/admin'); ?>

<?php $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open('/admin/league/insert') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un nouveau championnat</span>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label" for="name">Nom du championnat <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="name" value="<?=old('name')?>" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label" for="id_season">Saison</label>
                            <select class="form-select" name="id_season" id="id_season" required>
                                <?php foreach($seasons as $season):?>
                                    <option value="<?= $season['id'] ?>"><?= $season['name'] ?></option>
                                <?php endforeach ; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="id_category">Catégorie</label>
                            <select class="form-select" name="id_category" id="id_category" required>
                                <?php foreach($categories as $category):?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach ; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le championnat</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des championnats</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="leaguesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Nom du championnat</th>
                            <th>Saison</th>
                            <th>Catégorie</th>
                            <!-- <th>Nombre de membres ayant ce rôle</th>-->
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
    <div class="modal" id="modalLeague" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le rôle </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="modalNameInput">Nom du rôle</label>
                    <input class="form-control" id="modalNameInput" type="text">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="id_season">Saison</label>
                            <select class="form-select" name="modalSelectIdSeason" id="modalSelectIdSeason">
                                <?php foreach($seasons as $season) { ?>
                                    <option value="<?= $season['id'] ?>"> <?= $season['name']?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="id_category">Catégorie</label>
                            <select class="form-select" name="modalSelectIdCategory" id="modalSelectIdCategory">
                                <?php foreach($categories as $category) { ?>
                                    <option value="<?= $category['id'] ?>"> <?= $category['name']?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
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
        table = $('#leaguesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'leagueModel'
                }
            },
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    orderable: false,
                    width: '150px',
                    render: function (data, type, row) {
                        const isActive = row.deleted_at===null;
                        const toggleButton = isActive
                        ?
                            `
                                <button
                                    class="btn btn-sm btn-success btn-toggleActive-league"
                                    title="Désactiver"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-on"></i>
                                </button>
                            `
                        :
                            `
                            <button
                                    class="btn btn-sm btn-danger btn-toggleActive-league"
                                    title="Activer"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-off"></i>
                                </button>
                            `
                        return `
                            <div class="btn-group" role="group">
                                <button
                                    class="btn btn-sm btn-warning btn-edit-league"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-name='${escapeHtml(row.name)}'
                                    data-season-id='${row.id_season}'
                                    data-category-id='${row.id_category}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                               ${toggleButton}
                            </div>
                        `
                        ;
                    }
                },
                {data: 'id'},
                {data: 'name'},
                {data: 'season_name'},
                {data: 'category_name'}
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
    const myModal = new bootstrap.Modal('#modalLeague');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-league', function() {
        const btn = $(this);
        const seasonId = btn.data('season-id');
        const categoryId = btn.data('category-id');

        $('#modalNameInput').val(btn.data('name'));
        $('#modalNameInput').data('id',btn.data('id'));
        $('#modalSelectIdSeason').val(String(seasonId));
        $('#modalSelectIdCategory').val(String(categoryId));

        myModal.show();
    });

    //Fonction pour appeler la fonction de désactivation/activation
    $(document).on('click','.btn-toggleActive-league', function(){
       toggleActive($(this).data('id'));
    })

    function saveLeague () {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        let seasonId = $('#modalSelectIdSeason').val();
        let categoryId = $('#modalSelectIdCategory').val();

        $.ajax({
            url: baseUrl + 'admin/league/update/'+id,
            type:'POST',
            data: {
                name: name,
                id_season: seasonId,
                id_category: categoryId
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

    function toggleActive(leagueId) {
        $.ajax({
            url: '<?= base_url('admin/league/switch-active/') ?>' + leagueId,
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
                    $('#leaguesTable').DataTable().ajax.reload(null, false);

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
<?php $this->endSection(); ?>
