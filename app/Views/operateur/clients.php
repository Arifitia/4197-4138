<?php
$pageTitle  = 'Comptes clients';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<h1 class="mc-page-title h3"><i class="bi bi-people"></i> Situation des comptes clients</h1>

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap gap-4">
        <div>
            <div class="text-muted small">Nombre de clients</div>
            <div class="fs-4 fw-bold"><?= count($clients) ?></div>
        </div>
        <div>
            <div class="text-muted small">Solde total cumulé</div>
            <div class="fs-4 fw-bold">
                <?= number_format(array_sum(array_column($clients, 'solde')), 0, ',', ' ') ?> Ar
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($clients)) : ?>
            <div class="mc-empty">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Aucun client enregistré pour le moment.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
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
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
