<?php
$pageTitle  = 'Situation des gains';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';

$libelles = ['depot' => 'Dépôt', 'retrait' => 'Retrait', 'transfert' => 'Transfert'];
?>

<h1 class="mc-page-title h3"><i class="bi bi-graph-up-arrow"></i> Situation des gains</h1>
<p class="text-muted mb-4">Opérateur : <strong><?= esc($operateur_nom ?? 'MVola') ?></strong> — Gains générés par les frais perçus sur les retraits et les transferts.</p>

<div class="row g-3 mb-4">
    <?php foreach ($lignes as $ligne) : ?>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">
                        <?= esc($libelles[$ligne['type_operation']] ?? $ligne['type_operation']) ?>
                    </div>
                    <div class="fs-4 fw-bold mb-1"><?= number_format((float) $ligne['total_frais'], 0, ',', ' ') ?> Ar</div>
                    <div class="text-muted small"><?= (int) $ligne['nombre_operations'] ?> opération(s)</div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100 mc-solde-card">
            <div class="card-body">
                <div class="mc-solde-label">Total des gains</div>
                <div class="mc-solde-amount fs-3"><?= number_format((float) $total, 0, ',', ' ') ?> Ar</div>
            </div>
        </div>
    </div>
</div>

<h2 class="h5 mt-5 mb-3"><i class="bi bi-arrow-right-circle me-1"></i> Montants envoyés vers opérateurs externes</h2>
<p class="text-muted mb-4">Transferts effectués vers des numéros Airtel ou Orange. Ces montants serviront aux règlements inter-opérateurs.</p>

<div class="row g-3 mb-4">
    <?php if (empty($transferts_externes)) : ?>
        <div class="col-12">
            <div class="mc-empty">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Aucun transfert externe enregistré.
            </div>
        </div>
    <?php else : ?>
        <?php foreach ($transferts_externes as $ligne) : ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase">
                            <?= esc($ligne['operateur_externe']) ?>
                        </div>
                        <div class="fs-4 fw-bold mb-1"><?= number_format((float) $ligne['total_montant'], 0, ',', ' ') ?> Ar</div>
                        <div class="text-muted small"><?= (int) $ligne['nombre_operations'] ?> opération(s)</div>
                        <div class="text-muted small mt-2">Frais perçus : <?= number_format((float) $ligne['total_frais'], 0, ',', ' ') ?> Ar</div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="card h-100 mc-solde-card">
            <div class="card-body">
                <div class="mc-solde-label">Total des montants externes</div>
                <div class="mc-solde-amount fs-3"><?= number_format((float) $total_externe, 0, ',', ' ') ?> Ar</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">Détail par type d'opération</div>
    <div class="card-body p-0">
        <?php if (empty($lignes)) : ?>
            <div class="mc-empty">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Aucun gain enregistré pour le moment.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Type d'opération</th>
                            <th class="text-end">Nombre d'opérations</th>
                            <th class="text-end">Total des frais perçus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lignes as $ligne) : ?>
                        <tr>
                            <td><?= esc($libelles[$ligne['type_operation']] ?? $ligne['type_operation']) ?></td>
                            <td class="text-end"><?= (int) $ligne['nombre_operations'] ?></td>
                            <td class="text-end fw-semibold"><?= number_format((float) $ligne['total_frais'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="2" class="text-end">Total général</th>
                            <th class="text-end"><?= number_format((float) $total, 0, ',', ' ') ?> Ar</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
