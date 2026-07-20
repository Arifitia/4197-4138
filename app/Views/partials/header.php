<?php
/**
 * Partial commun : ouverture du document, navbar, messages flash.
 */
$pageTitle  = $pageTitle ?? 'MVola';
$activeMenu = $activeMenu ?? null;
$isClient   = (bool) session('client_id');
$isOperateur = (bool) (session('operateur_role') === 'operateur');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($pageTitle) ?> - MVola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-mobicash mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">
            <i class="bi bi-wallet2"></i> MVola
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mcNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mcNavbar">
            <?php if ($isClient): ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeMenu === 'client' ? 'fw-bold' : '' ?>" href="<?= site_url('dashboard') ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text badge rounded-pill badge-phone px-3 py-2 me-2">
                        <i class="bi bi-telephone-fill me-1"></i><?= esc(session('client_numero')) ?>
                    </span>
                    <a href="<?= site_url('dashboard/logout') ?>" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                    </a>
                </div>
            <?php elseif ($isOperateur): ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeMenu === 'operateur' ? 'fw-bold' : '' ?>" href="<?= site_url('operateur/dashboard') ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-1"></i> Gestion
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('prefixes') ?>">Préfixes opérateur</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('baremes') ?>">Barèmes de frais</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('types-operations') ?>">Types d'opérations</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= site_url('operateur/clients') ?>">Comptes clients</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('operateur/gains') ?>">Situation des gains</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text badge rounded-pill badge-phone px-3 py-2 me-2">
                        <i class="bi bi-building me-1"></i><?= esc(session('operateur_nom')) ?>
                    </span>
                    <a href="<?= site_url('operateur/auth/logout') ?>" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                    </a>
                </div>
            <?php else: ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> Espace client
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('auth') ?>"><i class="bi bi-box-arrow-in-right me-1"></i> Se connecter</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear"></i> Espace opérateur
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('operateur/auth') ?>"><i class="bi bi-box-arrow-in-right me-1"></i> Se connecter</a></li>
                        </ul>
                    </li>
                </ul>
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
