<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <?= form_open('/admin/role/save') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un nouveau rôle</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="name">Nom du role</label>
                    <input class="form-control" type="text" name="name" id="name">
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le rôle</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <div class="col-md-8">
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
<!--                            <th>Nombre de membres ayant ce rôle</th>-->
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
                    orderable: false,
                    width: '150px',
                    render: function (data, type, row) {
                        return
                        `
                            <div class="btn-group" role="group">
                                <button onclick=""  class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="" class="btn btn-sm btn-danger" title="Supprimer">
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
            $order: [[0, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        });

        // Fonction pour actualiser la table
        window.refreshTable = function () {
            table.ajax.reload(null, false); // false pour garder la pagination
        };
    });
</script>
<style>

</style>
<?= $this->endSection() ?>
