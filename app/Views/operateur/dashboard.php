<?php
$pageTitle = 'Tableau de bord opérateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Espace opérateur</h1>
    <p class="mvola-page-subtitle">Bienvenue, <?= esc($operateur_nom ?? 'MVola') ?></p>
</div>

<!-- KPI Cards -->
<div class="mvola-kpi-grid mvola-stagger">
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon blue">
            <i class="bi bi-people"></i>
        </div>
        <div class="mvola-kpi-label">Clients</div>
        <div class="mvola-kpi-value" data-counter="<?= (int) $totalClients ?>" data-duration="1500">
            <?= (int) $totalClients ?>
        </div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon green">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <div class="mvola-kpi-label">Transactions</div>
        <div class="mvola-kpi-value" data-counter="<?= (int) $totalTransactions ?>" data-duration="1500">
            <?= (int) $totalTransactions ?>
        </div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon yellow">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div class="mvola-kpi-label">Gains</div>
        <div class="mvola-kpi-value"><?= number_format((float) ($totalGains ?? 0), 0, ',', ' ') ?> Ar</div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>