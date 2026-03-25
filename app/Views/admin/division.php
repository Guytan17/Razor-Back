<?php $this->extend('layouts/admin'); ?>

<?php $this->section('content'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open('/admin/division/insert') ?>
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
                    <table class="table table-striped" id="divisionsTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Nom du championnat</th>
                            <th>Saison</th>
                            <th>Catégorie</th>
                            <th>Équipes</th>
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
    <div class="modal" id="modalDivision" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le championnat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="modalNameInput">Nom du championnat <span class="text-danger">*</span></label>
                            <input class="form-control" id="modalNameInput" type="text">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="id_season">Saison <span class="text-danger">*</span></label>
                            <select class="form-select" name="modalSelectIdSeason" id="modalSelectIdSeason">
                                <?php foreach($seasons as $season) { ?>
                                    <option value="<?= $season['id'] ?>"> <?= $season['name']?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="id_category">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-select" name="modalSelectIdCategory" id="modalSelectIdCategory">
                                <?php foreach($categories as $category) { ?>
                                    <option value="<?= $category['id'] ?>"> <?= $category['name']?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <!--START : Zone pour ajouter des équipes -->
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header text-center">
                                    <span class="card-title fw-bold h5">Équipes engagées</span>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <div class="input-group">
                                                <select class="form-select select-team" id="modalSelectTeam">
                                                </select>
                                                <span class="input-group-text btn btn-sm btn-primary d-flex align-items-center" id="add-team"><i class="fas fa-plus"></i> Ajouter</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="zone-modal-teams">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--END : Zone pour ajouter des équipes -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button onclick="saveDivision()" type="button" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END : MODAL POUR LES MODIFICATIONS -->
</div>
<script>
    var baseUrl = "<?=base_url();?>";

    $(document).ready(function() {
        table = $('#divisionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'DivisionModel'
                }
            },
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    orderable: false,
                    width: '100px',
                    render: function (data, type, row) {
                        const isActive = row.deleted_at===null;
                        const toggleButton = isActive
                        ?
                            `
                                <button
                                    class="btn btn-sm btn-success btn-toggleActive-division"
                                    title="Désactiver"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-on"></i>
                                </button>
                            `
                        :
                            `
                            <button
                                    class="btn btn-sm btn-danger btn-toggleActive-division"
                                    title="Activer"
                                    data-id="${row.id}">
                                        <i class="fas fa-toggle-off"></i>
                                </button>
                            `
                        return `
                            <div class="btn-group" role="group">
                                <button
                                    class="btn btn-sm btn-warning btn-edit-division"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-name='${escapeHtml(row.name)}'
                                    data-season-id='${row.id_season}'
                                    data-season-name='${escapeHtml(row.season_name)}'
                                    data-category-id='${row.id_category}'
                                    data-category-name='${escapeHtml(row.category_name)}'
                                    data-teams='${row.teams_data}'>
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
                {data: 'category_name'},
                {data: 'teams_name'}
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

        //initialisation modalSelectTeam
        initAjaxSelect2(`#modalSelectTeam`, {dropdownParent:$('#modalDivision') ,url:'/admin/team/search', searchFields: 'name', placeholder:'Rechercher un équipe',extraParams:{id_club: 1}});
    });

    //Définition de la modal
    const myModal = new bootstrap.Modal('#modalDivision');
    let nbTeams = $('#zone-modal-teams .row-team').length;

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-division', function() {
        const btn = $(this);
        const seasonId = btn.data('season-id');
        const categoryId = btn.data('category-id');
        const teams = btn.data('teams');
        console.log(teams);

        $('#modalNameInput').val(btn.data('name'));
        $('#modalNameInput').data('id',btn.data('id'));
        $('#modalSelectIdSeason').val(String(seasonId));
        $('#modalSelectIdCategory').val(String(categoryId));
        $('#zone-modal-teams').empty()

        //Boucle pour afficher les équipes déjà affiliées au championnat
        if(teams && teams.length>0 ) {

            teams.filter(team => team !== null).forEach(team => {
                nbTeams++;
                let rowTeam = `
            <div class="row mb-3 row-team">
                <div class="col">
                    <div class="card card-team">
                        <div class="card-body p-1 d-flex align-items-center">
                            <div class="row">
                               <div class="col-auto">
                                   <span class="fs-4"><i class="fas fa-trash-alt text-danger delete-team-button"></i></span>
                               </div>
                                <div class="col d-flex align-items-center">
                                    <span class="fw-semibold span-team" id="modal-team-${nbTeams}" data-team-id="${team.id}">${team.name +' - '+team.category+' - '+team.season}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

                $('#zone-modal-teams').append(rowTeam);
            });
        }


        myModal.show();
    });

    //Fonction pour appeler la fonction de désactivation/activation
    $(document).on('click','.btn-toggleActive-division', function(){
       toggleActive($(this).data('id'));
    })

    //Fonction au clic sur l'ajout d'une équipe (édition)
    $('#add-team').on('click', function(){
        nbTeams++;
        let selectedTeam = $('#modalSelectTeam').select2('data');

        // si aucune équipe n'est sélectionnée lors du clic, on bloque la création de la row
        if (!selectedTeam.length) {
            return;
        }
        let team = selectedTeam[0];

        console.log(team);

        let row = `
            <div class="row mb-3 row-team">
                <div class="col">
                    <div class="card card-team">
                        <div class="card-body p-1 d-flex align-items-center">
                            <div class="row">
                               <div class="col-auto">
                                   <span class="fs-4"><i class="fas fa-trash-alt text-danger delete-team-button"></i></span>
                               </div>
                                <div class="col d-flex align-items-center">
                                    <span class="fw-semibold span-team" id="modal-team-${nbTeams}" data-team-id='${team.id}'>${team.text}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#zone-modal-teams').prepend(row);
        $('#modalSelectTeam').empty();
    })

    //action sur le bouton pour supprimer une équipe
    $('#zone-modal-teams').on('click','.delete-team-button', function(){
        $(this).closest('.row-team').remove();
    })

    function saveDivision () {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        let seasonId = $('#modalSelectIdSeason').val();
        let categoryId = $('#modalSelectIdCategory').val();
        let teams = [];
        $('.span-team').each(function () {
            let teamData = $(this).data('team-id');
            teams.push(teamData);
        })

        console.log(teams);


        $.ajax({
            url: baseUrl + 'admin/division/update/'+id,
            type:'POST',
            headers : {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                name: name,
                id_season: seasonId,
                id_category: categoryId,
                teams: teams,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                if(response.success){
                    myModal.hide();
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
                        html: getAjaxErrorMessage(response),
                        icon: 'error'
                    });
                }
            }
        })
    }

    function toggleActive(divisionId) {
        $.ajax({
            url: '<?= base_url('admin/division/switch-active/') ?>' + divisionId,
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
                    $('#divisionsTable').DataTable().ajax.reload(null, false);

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
    .delete-team-button:hover {
        scale:1.20;
        cursor:pointer;
    }
</style>
<?php $this->endSection(); ?>
