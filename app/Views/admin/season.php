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
    <!-- START : MODAL POUR LES MODIFICATIONS -->
    <div class="modal" id="modalSeason" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la saison </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label" for="modalNameInput">Nom de la saison</label>
                    <input class="form-control" id="modalNameInput" type="text">
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="start_date">Date de début de saison</label>
                            <input class="form-control" type="date" name="modalStartDateInput" id="modalStartDateInput">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="end_date">Date de fin de saison</label>
                            <input class="form-control" type="date" name="modalEndDateInput" id="modalEndDateInput">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button onclick="saveSeason()" type="button" class="btn btn-primary">Sauvegarder</button>
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
                        if(type ==='sort'){
                            return data //On laisse les données brutes pour le tri pour que ça reste chronologique et pas alphabétique
                        } else {
                            return formatDateFr(data)
                        }
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

    //Définition de la modal
    const myModal = new bootstrap.Modal('#modalSeason');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-season', function() {
        const btn = $(this);

        $('#modalNameInput').val(btn.data('name'));
        $('#modalNameInput').data('id',btn.data('id'));
        $('#modalStartDateInput').val(btn.data('start'));
        $('#modalEndDateInput').val(btn.data('end'));

        myModal.show();
    });

    //Fonction pour appeler la fonction de suppression
    $(document).on('click','.btn-delete-season', function(){
        deleteSeason($(this).data('id'));
    })

    function formatDateFr(dateStr) {
        if(!dateStr) return '';
        const [y,m, d] = dateStr.split('-');
        return `${d}/${m}/${y}`;
    }

    function saveSeason() {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        let startDate = $('#modalStartDateInput').val() || null;
        let endDate = $('#modalEndDateInput').val() || null;
        $.ajax({
            url: baseUrl + 'admin/season/update/' + id,
            type: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                name: name,
                start_date: startDate,
                end_date: endDate,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function (response) {
                myModal.hide();
                if (response.success) {
                    Swal.fire({
                        title: 'Succès !',
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

    function deleteSeason(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cette saison ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/season/delete/') ?>'+id,
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
<?= $this->endSection() ?>