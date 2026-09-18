<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ;?>

<div class="container-fluid">
    <div class="row">
    <!-- START : ZONE RANK-SPONSOR -->
        <div class="col-md-6">
            <!-- START : ZONE CREATION RANK -->
            <div class="card mb-3">
                <?= form_open('/admin/sponsors-params/insert-rank') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un rang d'importance des sponsors</span>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label" for="rank">Rang<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="code_type" id="rank" value="<?= old('rank')?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label" for="label">Label du rang <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="label" id="label" value="<?=old('label')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le rang d'importance des sponsors</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION RANK -->
            <!-- START : ZONE INDEX RANK -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des rangs d'importance des sponsors</span>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="ranksTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Rang</th>
                            <th>Libellé</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX RANK -->
        </div>
    <!-- END : ZONE RANK-SPONSOR -->

    <!-- START : ZONE DOTATION-TYPE -->
        <div class="col-md-6">
            <!-- START : ZONE CREATION TYPE -->
            <div class="card mb-3">
                <?= form_open('/admin/sponsors-params/insert-type') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un type dotation</span>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label" for="dotation-type">Type de dotation<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="dotation_type" id="dotation-type" value="<?= old('dotation-type')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le type de faute technique</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION TYPE -->
            <!-- START : ZONE INDEX TYPES DE DOTATION -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des types de dotation</span>
                </div>
                <div class="card-body overflow-auto">
                    <table class="table table-striped" id="typesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Types</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX TYPES DE DOTATION -->
        </div>
    <!-- END : ZONE DOTATION-TYPE -->
    </div>
</div>

<script>
    var baseUrl = "<?=base_url();?>";
    let tableRank;
    let tableType;

    $(document).ready(function() {
        //GESTION INDEX DES RANGS
        tableRank = $('#ranksTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'SponsorRankModel'
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
                                    class="btn btn-sm btn-warning btn-edit-rank"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-rank='${escapeHtml(row.rank)}'
                                    data-label='${escapeHtml(row.label)}'>
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-rank"
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
                {data: 'rank'},
                {data: 'label'},
            ],
            language: {
                url: baseUrl + 'assets/js/datatable/datatable-2.3.5-fr-FR.json',
            },
            order: [[1, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        })

        // Fonction pour actualiser la table des rangs
        window.refreshTableType = function () {
            tableRank.ajax.reload(null, false); // false pour garder la pagination
        }
        //GESTION INDEX DES TYPES DE DOTATION
        tableType = $('#typesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'datatable/searchdatatable',
                type: 'POST',
                data: {
                    model: 'DotationTypeModel'
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
                                    class="btn btn-sm btn-warning btn-edit-type"
                                    title="Modifier"
                                    data-id='${row.id}'
                                    data-type='${escapeHtml(row.type)}'
                                        <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-type"
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
                {data: 'type'},
            ],
            language: {
                url: baseUrl + 'assets/js/datatable/datatable-2.3.5-fr-FR.json',
            },
            order: [[1, 'desc']], // Tri par ID décroissant par défaut
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]]
        })
        // Fonction pour actualiser la table des types
        window.refreshTableType = function () {
            tableType.ajax.reload(null, false); // false pour garder la pagination
        }
    });


</script>

<?php $this->endSection() ?>
