<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?= form_open_multipart('admin/reglages/save', ['id' => 'settings_form']) ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Réglages du site</h1>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cog"></i> Paramètres généraux
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="site_title" class="form-label">Titre du site <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="site_title"
                                   name="site_title"
                                   value="<?= old('site_title', esc($site_title)) ?>"
                                   required
                                   minlength="3"
                                   maxlength="255">
                            <small class="text-muted">Le nom de votre site web (affiché dans les titres de pages).</small>
                        </div>
                        <div class="col-md-6">
                            <label for="contact_email" class="form-label">Email de contact <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control"
                                   id="contact_email"
                                   name="contact_email"
                                   value="<?= old('contact_email', esc($contact_email)) ?>"
                                   required
                                   maxlength="255">
                            <small class="text-muted">Adresse email pour les contacts (non utilisée actuellement).</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-image"></i> Logo du site
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="<?= $site_logo_url ?>"
                             alt="Logo du site"
                             id="logoPreview"
                             class="rounded border"
                             style="max-width: 200px; max-height: 100px; object-fit: contain;">
                        <?php if ($has_logo): ?>
                            <button type="button" class="btn btn-danger btn-sm" id="deleteLogoBtn" onclick="deleteLogo()">
                                <i class="fas fa-trash"></i> Supprimer le logo
                            </button>
                        <?php endif; ?>
                    </div>
                    <input type="file"
                           class="form-control"
                           id="site_logo"
                           name="site_logo"
                           accept="image/*">
                    <small class="text-muted">Logo affiché dans la navigation. Formats acceptés : JPG, PNG, SVG, GIF (Max: 2MB)</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-lg btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les réglages
                </button>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

<script>
$(document).ready(function() {
    // Initialiser la preview du logo
    initImagePreview('#site_logo', '#logoPreview', '<?= $site_logo_url ?>', 2);
});

function deleteLogo() {
    Swal.fire({
        title: 'Supprimer le logo ?',
        text: "Cette action est irréversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('admin/reglages/delete-logo') ?>',
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
                        // Mettre à jour l'aperçu avec l'image par défaut
                        $('#logoPreview').attr('src', response.defaultLogoUrl);
                        // Masquer le bouton supprimer
                        $('#deleteLogoBtn').hide();

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
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Erreur !',
                        text: 'Une erreur est survenue lors de la suppression.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}
</script>

<?= $this->endSection() ?>
