<div id="top-navbar" class="navbar bg-body-tertiary d-flex justify-content-end align-items-center p-3">
    <div class="me-auto">
        <?php if(isset($breadcrumb) && !empty($breadcrumb)) {
            echo view('templates/admin/breadcrumb',['breadcrumb' => $breadcrumb]);  }  ?>
    </div>
    <div class="dropdown dropstart">
        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php $user = auth()->user(); ?>
            <img src="<?= $user->getAvatarUrl() ?>" alt="Avatar" class="rounded-circle border border-secondary menu-avatar">
        </a>
        <ul class="dropdown-menu" >
            <li><span class="dropdown-header">Bonjour <?= esc($user->getFullName()) ?></span></li>
            <li><a class="dropdown-item" href="<?= base_url('') ?>"><i class="fa-solid fa-home text-primary"></i> Retour au site site</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fa-solid fa-power-off text-danger"></i> Déconnexion</a></li>
        </ul>
    </div>
</div>