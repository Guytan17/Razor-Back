<?php $this->extend('layouts/admin') ?>

<?php $this->section('content') ;?>

<div  class="container-fluid">
    <div class="row">
        <!-- START : ZONE TYPE -->
        <div class="col-md-6 mb-3">
            <!-- START : ZONE CREATION TYPE -->
            <div class="card mb-3">
                <?= form_open('/admin/technical-foul-params/insert-type') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'un type de faute technique</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="code-type">Code du type<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="code_type" id="code-type" value="<?=old('code-type')?>" required>
                    <div class="row mt-2">
                        <div class="col">
                            <label class="form-label" for="explanation-type">Explication du code <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="explanation-type" id="explanation-type" value="<?=old('explanation-type')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le type de faute technique</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION TYPE -->
            <!-- START : ZONE INDEX TYPE -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des types de faute technique</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="typesTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Explication du code</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX TYPE -->
        </div>
        <!-- END : ZONE TYPE -->
        <!-- START : ZONE CLASSIFICATION -->
        <div class="col-md-6 mb-3">
            <!-- START : ZONE CREATION CLASSIFICATION -->
            <div class="card mb-3">
                <?= form_open('/admin/technical-foul-params/insert-classification') ?>
                <div class="card-header">
                    <span class="card-title h5"> Création d'une classification de faute technique</span>
                </div>
                <div class="card-body">
                    <label class="form-label" for="code-classification">Code de la classification<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="code_classification" id="code-classification" value="<?=old('code-classification')?>" required>
                    <div class="row mt-2">
                        <div class="col">
                            <label class="form-label" for="explanation-classification">Explication du code <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="explanation-classification" id="explanation-classification" value="<?=old('explanation-classification')?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer la classification de faute technique</button>
                </div>
                <?= form_close() ?>
            </div>
            <!-- END : ZONE CREATION CLASSIFICATION -->
            <!-- START : ZONE INDEX CLASSIFICATION -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title h5">Liste des classifications de faute technique</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="classificationsTable">
                        <thead >
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Explication du code</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Chargé via Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END : ZONE INDEX CLASSIFICATION -->
        </div>
        <!-- END : ZONE CLASSIFICATION-->
    </div>
</div>
<script>
    var baseUrl = "<?=base_url();?>";

    $(document).ready(function() {

    }
</script>
<?php $this->endSection() ;?>
