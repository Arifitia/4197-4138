<?php
$pageTitle = 'Tableau de bord opérateur';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<h1 class="mc-page-title h3">
    <i class="bi bi-speedometer2"></i> Espace opérateur : <?= esc($operateur_nom ?? 'MVola') ?>
</h1>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1 text-primary"></i>
                <h5 class="card-title mt-2">Clients</h5>
                <p class="display-6 fw-bold"><?= (int) $totalClients ?></p>
                <a href="<?= site_url('operateur/clients') ?>" class="btn btn-outline-primary btn-sm">
                    Voir les comptes
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-currency-dollar fs-1 text-success"></i>
                <h5 class="card-title mt-2">Transactions</h5>
                <p class="display-6 fw-bold"><?= (int) $totalTransactions ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-graph-up-arrow fs-1 text-warning"></i>
                <h5 class="card-title mt-2">Gains</h5>
                <a href="<?= site_url('operateur/gains') ?>" class="btn btn-outline-warning btn-sm">
                    Voir la situation
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="<?= site_url('operateur/auth/logout') ?>" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right me-1"></i> Déconnexion opérateur
    </a>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>