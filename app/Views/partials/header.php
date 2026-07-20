<?php
/**
 * Partial commun : ouverture du document, navbar, messages flash.
 * Variables attendues (optionnelles) :
 *  - $pageTitle : titre affiché dans <title>
 *  - $activeMenu : 'client' | 'operateur' | null, pour surligner le menu actif
 */
$pageTitle  = $pageTitle ?? 'MobiCash';
$activeMenu = $activeMenu ?? null;
$isLoggedIn = (bool) session('client_id');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($pageTitle) ?> - MobiCash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-mobicash mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">
            <i class="bi bi-wallet2"></i> MobiCash
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mcNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mcNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $activeMenu === 'client' ? 'fw-bold' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> Espace client
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($isLoggedIn): ?>
                            <li><a class="dropdown-item" href="<?= site_url('dashboard') ?>"><i class="bi bi-speedometer2 me-1"></i> Tableau de bord</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= site_url('dashboard/logout') ?>"><i class="bi bi-box-arrow-right me-1"></i> Déconnexion</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="<?= site_url('auth') ?>"><i class="bi bi-box-arrow-in-right me-1"></i> Se connecter</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $activeMenu === 'operateur' ? 'fw-bold' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Espace opérateur
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= site_url('prefixes') ?>"><i class="bi bi-hash me-1"></i> Préfixes opérateur</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('baremes') ?>"><i class="bi bi-cash-coin me-1"></i> Barèmes de frais</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('types-operations') ?>"><i class="bi bi-list-check me-1"></i> Types d'opérations</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= site_url('operateur/clients') ?>"><i class="bi bi-people me-1"></i> Comptes clients</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('operateur/gains') ?>"><i class="bi bi-graph-up-arrow me-1"></i> Situation des gains</a></li>
                    </ul>
                </li>
            </ul>

            <?php if ($isLoggedIn): ?>
                <span class="navbar-text badge rounded-pill badge-phone px-3 py-2">
                    <i class="bi bi-telephone-fill me-1"></i><?= esc(session('client_numero')) ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
