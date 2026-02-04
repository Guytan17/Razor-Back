<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <!-- START : ZONE CREATION -->
            <div class="card">
                <?= form_open('/admin/season/insert') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'une nouvelle saison</span>
                </div>
                <div class="card-body">
                    <div class="row">
                       <div class="col">
                           <label class="form-label" for="name">Nom de la saison <span class="text-danger">*</span></label>
                           <input class="form-control" type="text" name="name" id="name" value="<?=old('name')?>" required>
                       </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="start_date">Date de début de saison</label>
                            <input class="form-control" type="date" name="start_date" id="start_date" value="<?=old('start_date')?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="end_date">Date de fin de saison</label>
                            <input class="form-control" type="date" name="end_date" id="end_date" value="<?=old('start_date')?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la saison</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION -->
        </div>
        <div class="col-md-8">
            <!-- START : ZONE INDEX -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des saisons </span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="seasonsTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Nom de la saison</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <!-- <th>Statut de la saison</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX -->
        </div>
    </div>
</div>
<script>
    var baseUrl = "<?=base_url();?>";
    var table;

    $(document).ready(function() {
        table = $('#seasonsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'SeasonModel'
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
                                    class="btn btn-sm btn-warning btn-edit-season"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-name='${escapeHtml(row.name)}'
                                    data-start='${escapeHtml(row.start_date)}'
                                    data-end='${escapeHtml(row.end_date)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-season"
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
                {
                    data: 'start_date',
                    render: function(data,type) {
                        if(type ==='display' || type ==='filter'){
                            return formatDateFr(data,type)
                        }
                        return data
                    }
                },
                {
                    data: 'end_date',
                    render: function(data,type) {
                        if(type ==='display' || type ==='filter'){
                            return formatDateFr(data,type)
                        }
                        return data
                    }
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

    })

    function formatDateFr(dateStr) {
        if(!dateStr) return '';
        const [y,m, d] = dateStr.split('-');
        return `${d}/${m}/${y}`;
    }
</script>
<?= $this->endSection() ?>