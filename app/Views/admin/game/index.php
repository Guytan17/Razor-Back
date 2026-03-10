<?php $this->extend('layouts/admin') ; ?>

<?php $this->section('content') ; ?>

<div class="container-fluid">
    <!-- START : ZONE POUR LES TOASTS -->
    <div class="row mb-3">
        <div class="col-12">
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- END : ZONE POUR LES TOASTS -->

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

<?php $this->endSection(); ?>
