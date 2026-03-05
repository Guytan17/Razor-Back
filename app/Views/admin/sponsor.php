<?php $this->extend('/layouts/admin'); ?>

<?php $this->section('content'); ?>


<div class="container-fluid">
    <div class="row">
        <!-- START : ZONE CRÉATION -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <?= form_open_multipart('admin/sponsor/insert') ?>
                    <div class="card-header">
                        <span class="card-title h5">Création d'un nouveau sponsor</span>
                    </div>
                    <div class="card-body">
                        <!-- IMAGE DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col text-center">
                                <img class="img-thumbnail mb-3" src="<?= esc(base_url('/assets/img/default.png')) ; ?>" title="image du sponsor" alt="image du sponsor " id="logoPreview">
                                <input class="form-control" type="file" name="logo" id="logo">
                            </div>
                        </div>
                        <!-- NOM DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="name">Nom du sponsor <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="name" required>
                            </div>
                        </div>
                        <!-- IMPORTANCE DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="rank">Niveau d'importance du sponsor <span class="text-danger">*</span></label>
                                <select class="form-select" name="rank" id="rank" required>
                                    <?php for ($i = 1; $i <= 9; $i++): ?>
                                    <option value="<?= $i ?>">Rang <?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <!-- SPECIFICATIONS DU SPONSOR -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="specifications">Caractéristiques et instructions</label>
                                <textarea class="form-control" name="specifications" id="specifications" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le sponsor</button>
                </div>
                <?= form_close() ; ?>
            </div>
        </div>
        <!-- END : ZONE CRÉATION -->
        <!-- START : ZONE INDEX -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des sponsors</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="sponsorsTable">
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
<!--                            <th>Logo</th>-->
                            <th>Nom du sponsor</th>
                            <th>Niveau d'importance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- chargé en Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END : ZONE INDEX -->
    </div>
    <!-- START : MODAL POUR LES MODIFICATIONS -->
    <div class="modal" id="modalSponsor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le sponsor </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- START : IMAGE -->
                    <div class="row mb-3 d-flex justify-content-center">
                        <div class="col-auto position-relative logo-hover m-0 p-0">
                            <div class="position-absolute img-thumbnail " style="height:100%;width:100%;background-color:rgb(0,0,0,0.3);display:none;" id="logoPreviewGreyDiv">
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <div class="btn btn-danger text-white delete-logo" id="delete-logo-btn">
                                        <i class="fas fa-trash-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <img class="img-thumbnail" src="" id="modalLogoPreview">
                        </div>
                    </div>
                    <input class="form-control" type="file" name="modalLogoInput" id="modalLogoInput">
                    <!-- END : IMAGE -->
                    <!-- START : DONNEES TEXTUELLES -->
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="modalNameInput">Nom du sponsor <span class="text-danger">*</span></label>
                            <input class="form-control" id="modalNameInput" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="modalRankInput">Niveau d'importance du sponsor <span class="text-danger">*</span></label>
                            <select class="form-select" name="rank" id="modalRankInput" required>
                                <?php for ($i = 1; $i <= 9; $i++): ?>
                                    <option value="<?= $i ?>">Rang <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="modalSpecificationsInput">Caractéristiques et instructions</label>
                            <textarea class="form-control" name="specifications" id="modalSpecificationsInput" rows="3"></textarea>
                        </div>
                    </div>
                    <!-- END : DONNEES TEXTUELLES -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button onclick="saveSponsor()" type="button" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END : MODAL POUR LES MODIFICATIONS -->
</div>
<script>
    var baseUrl = "<?=base_url();?>";

    $(document).ready(function() {
        // Initialiser l'aperçu du logo
        initImagePreview('#logo', '#logoPreview', '<?= esc(base_url('/assets/img/default.png'),'js') ?>', 2);

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
                        return `
                            <div class="btn-group" role="group">
                                <button
                                    class="btn btn-sm btn-warning btn-edit-sponsor"
                                    title="Modifier"
                                    data-id='${row.sponsor_id}'
                                    data-name='${escapeHtml(row.name)}'
                                    data-rank='${escapeHtml(row.rank)}'
                                    data-specifications='${escapeHtml(row.specifications)}'
                                    data-logo-url='${escapeHtml(row.logo_url)}'
                                    data-logo-id='${escapeHtml(row.logo_id)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-sponsor"
                                    title="Supprimer"
                                    data-id="${row.sponsor_id}">
                                        <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `
                            ;
                    }
                },
                {data: 'sponsor_id'},
                {data: 'name'},
                {data: 'rank'},
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

        //Apparition du bouton de suppression de l'image
        $('.logo-hover').on('mouseenter mouseleave', function(){
            $(this).find('.position-absolute').fadeToggle(50);
        });

        // Action du clic sur le bouton de suppression d'une image
        $('.delete-logo').on('click', function(e){
            e.preventDefault();
            let logoId = $(this).data('logo-id');
            $(this).append(`<input type="hidden" name="delete-logo" value="${logoId}" id='delete-logo' />`);
            $('#modalLogoPreview').attr('src', "<?= base_url('/assets/img/default.png') ?>");
            $('#modalLogoInput').val('');
        });
    });


    //Définition de la modal
    const myModal = new bootstrap.Modal('#modalSponsor');

    //Fonction pour ouvrir la modal avec les données préremplies
    $(document).on('click','.btn-edit-sponsor', function() {
        const btn = $(this);
        let name = btn.data('name');

        $('#modalNameInput').val(name);
        $('#modalNameInput').data('id',btn.data('id'));
        $('#modalRankInput').val(btn.data('rank'));
        $('#modalSpecificationsInput').val(btn.data('specifications'));
        let logoUrl = btn.data('logo-url');
        $('#modalLogoPreview').attr('src', logoUrl || '/assets/img/default.png').attr('title',`logo de ${name} ` ).attr('alt', `logo de ${name} `);
        $('#delete-logo-btn').data('logo-id',btn.data('logo-id'));

        initImagePreview('#modalLogoInput', '#modalLogoPreview', '<?= esc(base_url('/assets/img/default.png'),'js') ?>', 2);

        myModal.show();
    });

    //action au clic sur le bouton de suppression d'un sponsor
    $(document).on('click', '.btn-delete-sponsor', function () {
        let id = $(this).data('id');
        deleteSponsor(id);
    });

    function saveSponsor() {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        let rank = $('#modalRankInput').val() || null;
        let specifications = $('#modalSpecificationsInput').val() || null;
        let logoFile = $('#modalLogoInput')[0].files[0];
        let deleteLogo = $('#delete-logo').val() || null;

        //FormData pour envoyer le logo
        let formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('rank', rank);
        formData.append('specifications', specifications);
        formData.append('delete-logo', deleteLogo);
        formData.append(csrfName,csrfHash);

        //ajout du logo que s'il y en a un
        if(logoFile) {
            formData.append('logo', logoFile);
        }

        $.ajax({
            url: baseUrl + 'admin/sponsor/update/' + id,
            type: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: formData,
            dataType: 'json',
            //indispensable avec FormData
            processData: false, // empeche que les données soient traduites en string
            contentType: false, // laisse le navigateur gérer le type de formulaire
            success: function (response) {
                if (response.success) {
                    myModal.hide();
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
                        html: getAjaxErrorMessage(response),
                        icon: 'error'
                    });
                }
            }
        })
    }

    function deleteSponsor(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer ce sponsor ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/admin/sponsor/delete/') ?>'+id,
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
     #modalLogoPreview,#logoPreview {
    max-height: 250px;
    }
</style>
<?php $this->endSection();