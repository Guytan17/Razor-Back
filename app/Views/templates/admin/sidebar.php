<nav id="sidebar" class="navbar navbar-expand-lg bg-body-secondary flex-row align-items-start p-3">
    <div id="top-sidebar" class="d-flex justify-content-between align-items-center">
        <img src="<?= get_site_logo(); ?>" alt="Logo" class="img-fluid menu-avatar">
        <a class="navbar-brand" href="<?= base_url('/admin');?>"><?= setting('App.siteName') ?? 'Mon Site'; ?></a>
        <div id="toggle-sidebar" data-collapse="false" data-bs-toggle="tooltip" data-bs-placement="right" title="Toggle Sidebar">
            <i class="fas fa-arrow-left" id="sidebarCollapse"></i>
        </div>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse flex-column w-100" id="navbarNavDropdown">
        <ul class="navbar-nav flex-column w-100 align-self-start flex-grow-1">
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

                // Si un enfant est actif, le parent doit être ouvert
                $isExpanded = $hasActiveChild;
                ?>
                <li class="nav-item <?= $hasSubs ? 'dropdown' : '' ?>">
                    <a class="nav-link <?= $hasSubs ? 'dropdown-toggle' : '' ?> <?= $class ?> <?= $isActive ? 'active' : '' ?>"
                       href="<?= $hasSubs ? '#' : base_url($menuItem['url']) ?>"
                            <?= $hasSubs ? 'role="button" data-bs-toggle="dropdown" aria-expanded="' . ($isExpanded ? 'true' : 'false') . '"' : '' ?>>

                        <?php if ($icon) : ?>
                            <span data-bs-toggle="tooltip" data-bs-placement="right" title="<?= $menuItem['title'] ?>">
                                <i class="<?= $icon ?>"></i>
                            </span>
                        <?php endif; ?>
                        <span class="link-text"><?= $menuItem['title'] ?></span>
                    </a>

                    <?php if ($hasSubs) : ?>
                        <ul class="dropdown-menu ps-2 py-0 <?= $isExpanded ? 'show' : '' ?>">
                            <?php foreach($menuItem['subs'] as $subKey => $submenu) :
                                if (empty($submenu['title']) || empty($submenu['url'])) continue;
                                $subIcon = $submenu['icon'] ?? null;
                                $isSubActive = ($subKey === $menu);
                                ?>
                                <li>
                                    <a href="<?= base_url($submenu['url']) ?>" class="nav-link <?= $isSubActive ? 'active' : '' ?>">
                                        <?php if ($subIcon) : ?>
                                            <span data-bs-toggle="tooltip" data-bs-placement="right" title="<?= $submenu['title'] ?>">
                                                <i class="<?= $subIcon ?>"></i>
                                            </span>
                                        <?php endif; ?>
                                        <span class="link-text"><?= $submenu['title'] ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="navbar-nav sidebar-footer d-flex w-100 justify-content-between">
            <small class="text-secondary">v1.0.0</small>
            <div id="themeToggle" class="ms-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Tooltip on left">
                <i class="fas fa-lightbulb" style="cursor:pointer"></i>
            </div>
        </div>
    </div>
</nav>