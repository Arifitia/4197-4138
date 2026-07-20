<?php
$pageTitle = 'Comptes clients';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Situation des comptes clients</h1>
    <p class="mvola-page-subtitle">Opérateur : <strong><?= esc(session('operateur_nom')) ?></strong></p>
</div>

<div class="mvola-card mb-4">
    <div class="mvola-card-body">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="mvola-kpi-card" style="border: none; box-shadow: none; padding: 0;">
                    <div class="mvola-kpi-label">Nombre de clients</div>
                    <div class="mvola-kpi-value"><?= count($clients) ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mvola-kpi-card" style="border: none; box-shadow: none; padding: 0;">
                    <div class="mvola-kpi-label">Solde total cumulé</div>
                    <div class="mvola-kpi-value"><?= number_format(array_sum(array_column($clients, 'solde')), 0, ',', ' ') ?> Ar</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mvola-card">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-people"></i> Liste des clients
        </div>
    </div>
    <div class="mvola-card-body p-0">
        <?php if (empty($clients)) : ?>
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucun client enregistré pour le moment.</div>
            </div>
        <?php else : ?>
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Numéro de téléphone</th>
                                <th class="text-end">Solde</th>
                                <th>Client depuis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $c) : ?>
                                <tr>
                                    <td><?= esc($c['id']) ?></td>
                                    <td><i class="bi bi-telephone me-1 text-muted"></i><?= esc($c['numero_telephone']) ?></td>
                                    <td class="text-end fw-semibold"><?= number_format((float) $c['solde'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= esc(date('d/m/Y', strtotime($c['date_creation']))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>