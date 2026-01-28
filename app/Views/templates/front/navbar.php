<?php if (isset($menus)) : ?>
<nav id="navbar" class="navbar navbar-expand-lg bg-body-secondary align-items-start p-3">
    <div class="container">

    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav align-self-start">
            <?php foreach($menus as $menuKey => $menuItem) :
                // Vérification simple : on saute si pas de titre ou URL
                if (empty($menuItem['title']) || empty($menuItem['url'])) continue;

                $hasSubs = !empty($menuItem['subs']);
                $icon = $menuItem['icon'] ?? null;
                $class = $menuItem['class'] ?? '';

                // Vérifier si ce menu ou un de ses sous-menus est actif
                $isActive = ($menuKey === $menu);
                $hasActiveChild = false;

                if ($hasSubs) {
                    foreach($menuItem['subs'] as $subKey => $submenu) {
                        if ($subKey === $menu) {
                            $hasActiveChild = true;
                            break;
                        }
                    }
                }

                // En front, si un enfant est actif, on met le parent en actif (pas d'ouverture du dropdown)
                $isParentActive = $isActive || $hasActiveChild;
                ?>

                <?php if ($hasSubs) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $class ?> <?= $isParentActive ? 'active' : '' ?>"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <?php if ($icon) : ?>
                                <i class="<?= $icon ?>"></i>
                            <?php endif; ?>
                            <?= $menuItem['title'] ?>
                        </a>
                        <ul class="dropdown-menu py-0">
                            <?php foreach($menuItem['subs'] as $subKey => $submenu) :
                                if (empty($submenu['title']) || empty($submenu['url'])) continue;
                                $subIcon = $submenu['icon'] ?? null;
                                ?>
                                <li>
                                    <a href="<?= base_url($submenu['url']) ?>" class="nav-link">
                                        <?php if ($subIcon) : ?>
                                            <i class="<?= $subIcon ?>"></i>
                                        <?php endif; ?>
                                        <?= $submenu['title'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a href="<?= base_url($menuItem['url']) ?>" class="nav-link <?= $class ?> <?= $isActive ? 'active' : '' ?>">
                            <?php if ($icon) : ?>
                                <i class="<?= $icon ?>"></i>
                            <?php endif; ?>
                            <?= $menuItem['title'] ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>

        </ul>

        <div class="ms-auto dropdown dropstart">
            <a id="user-dropdown" class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php if (auth()->loggedIn()) : ?>
                    <?php $user = auth()->user(); ?>
                    <img src="<?= $user->getAvatarUrl() ?>" alt="Avatar" class="rounded-circle border border-secondary menu-avatar">
                <?php else : ?>
                <i class="fa-solid fa-user"></i>
                <?php endif; ?>
        </a>
            <ul class="dropdown-menu" >
             <?php if (auth()->loggedIn()) : ?>
                 <li><span class="dropdown-header">Bonjour <?= esc($user->getFullName()) ?></span></li>

                 <?php if ($user->inGroup('admin')) : ?>
                     <li><a class="dropdown-item" href="<?= base_url('/admin'); ?>"><i class="fa-solid fa-chart-line text-primary"></i></i> Tableau de bord</a></li>
                     <li class="divider dropdown-divider"></li>
                 <?php endif; ?>
                 <li><a class="dropdown-item" href="<?= base_url('account/profile') ?>"><i class="fa-solid fa-user-pen text-primary"></i></i> Mon compte</a></li>
                <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fa-solid fa-power-off text-danger"></i> Déconnexion</a></li>
             <?php else : ?>
                <li><a class="dropdown-item" href="<?= base_url('/login'); ?>"><i class="fa-solid fa-power-off text-success"></i> Se connecter</a></li>
                 <li><a class="dropdown-item" href="<?= base_url('/register'); ?>"><i class="fa-solid fa-user-plus text-primary"></i> S'inscrire</a></li>
             <?php endif; ?>
                <li class="divider dropdown-divider"></li>
                <li class="text-center">
                    <div id="themeToggle" class="dropdown-item" style="cursor:pointer" data-bs-toggle="tooltip" data-bs-placement="left">
                        <i class="fas fa-lightbulb" ></i>
                    </div>
                </li>
            </ul>
        </div>

    </div>
    </div>
</nav>
<?php endif; ?>