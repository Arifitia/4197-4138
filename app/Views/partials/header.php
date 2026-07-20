<?php
$pageTitle  = $pageTitle ?? 'MVola';
$activeMenu = $activeMenu ?? null;
$isClient   = (bool) session('client_id');
$isOperateur = (bool) (session('operateur_role') === 'operateur');

if (! $isClient && ! $isOperateur) {
    return redirect()->to('/auth')->with('error', 'Veuillez vous connecter.');
}

$uri = service('uri');
$segment1 = $uri->getSegment(1) ?? '';
$segment2 = $uri->getSegment(2) ?? '';

if ($isClient && ! $activeMenu) {
    if ($segment1 === 'historique') {
        $activeMenu = 'historique';
    } else {
        $activeMenu = 'client';
    }
} elseif ($isOperateur && ! $activeMenu) {
    if ($segment2 === 'clients') {
        $activeMenu = 'clients';
    } elseif ($segment2 === 'gains') {
        $activeMenu = 'gains';
    } elseif ($segment2 === 'configuration') {
        $activeMenu = 'configuration';
    } elseif ($segment1 === 'prefixes') {
        $activeMenu = 'prefixes';
    } elseif ($segment1 === 'baremes') {
        $activeMenu = 'baremes';
    } elseif ($segment1 === 'types-operations') {
        $activeMenu = 'types';
    } else {
        $activeMenu = 'operateur';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> - MVola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/variables.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/components.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/animations.css') ?>" rel="stylesheet">
</head>
<body>

<?php if ($isClient || $isOperateur): ?>
    <button class="mvola-sidebar-toggle" aria-label="Ouvrir le menu">
        <i class="bi bi-list"></i>
    </button>
    <div class="mvola-sidebar-overlay"></div>
    <aside class="mvola-sidebar">
        <div class="mvola-sidebar-brand">
            <div class="brand-icon">M</div>
            <div class="brand-text">MVola</div>
        </div>
        <nav class="mvola-sidebar-nav">
            <?php if ($isClient): ?>
                <div class="nav-section"><div class="nav-section-title">Menu principal</div></div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'client' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Tableau de bord
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'historique' ? 'active' : '' ?>" href="<?= site_url('historique') ?>">
                        <i class="bi bi-clock-history"></i> Historique
                    </a>
                </div>
            <?php elseif ($isOperateur): ?>
                <div class="nav-section"><div class="nav-section-title">Menu principal</div></div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'operateur' ? 'active' : '' ?>" href="<?= site_url('operateur/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Tableau de bord
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'clients' ? 'active' : '' ?>" href="<?= site_url('operateur/clients') ?>">
                        <i class="bi bi-people"></i> Comptes clients
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'gains' ? 'active' : '' ?>" href="<?= site_url('operateur/gains') ?>">
                        <i class="bi bi-graph-up-arrow"></i> Gains
                    </a>
                </div>
                <div class="nav-section"><div class="nav-section-title">Gestion</div></div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'prefixes' ? 'active' : '' ?>" href="<?= site_url('prefixes') ?>">
                        <i class="bi bi-hash"></i> Préfixes
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'baremes' ? 'active' : '' ?>" href="<?= site_url('baremes') ?>">
                        <i class="bi bi-cash-coin"></i> Barèmes
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'types' ? 'active' : '' ?>" href="<?= site_url('types-operations') ?>">
                        <i class="bi bi-list-check"></i> Types d'opérations
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'configuration' ? 'active' : '' ?>" href="<?= site_url('operateur/configuration') ?>">
                        <i class="bi bi-gear"></i> Configuration
                    </a>
                </div>
            <?php endif; ?>
        </nav>
        <div class="mvola-sidebar-footer">
            <?php if ($isClient): ?>
                <div class="user-info">
                    <div class="user-avatar"><i class="bi bi-person"></i></div>
                    <div class="user-details">
                        <div class="user-phone"><?= esc(session('client_numero')) ?></div>
                        <div class="user-role">Client MVola</div>
                    </div>
                </div>
                <a href="<?= site_url('dashboard/logout') ?>" class="mvola-btn mvola-btn-secondary mvola-btn-sm mvola-btn-block">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            <?php elseif ($isOperateur): ?>
                <div class="user-info">
                    <div class="user-avatar"><i class="bi bi-building"></i></div>
                    <div class="user-details">
                        <div class="user-phone"><?= esc(session('operateur_nom')) ?></div>
                        <div class="user-role">Opérateur</div>
                    </div>
                </div>
                <a href="<?= site_url('operateur/auth/logout') ?>" class="mvola-btn mvola-btn-secondary mvola-btn-sm mvola-btn-block">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            <?php endif; ?>
        </div>
    </aside>
<?php endif; ?>

<main class="mvola-main">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mvola-alert mvola-alert-success alert-dismissible fade show mvola-animate-fade-in-down" role="alert">
            <i class="bi bi-check-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="mvola-alert-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mvola-alert mvola-alert-danger alert-dismissible fade show mvola-animate-fade-in-down" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="mvola-alert-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>