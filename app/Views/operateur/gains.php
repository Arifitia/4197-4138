<?php
$pageTitle  = 'Situation des gains';
include __DIR__ . '/../partials/header.php';

$libelles = ['depot' => 'Dépôt', 'retrait' => 'Retrait', 'transfert' => 'Transfert'];
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Situation des gains</h1>
    <p class="mvola-page-subtitle">Opérateur : <strong><?= esc($operateur_nom ?? 'MVola') ?></strong> — Gains générés par les frais perçus.</p>
</div>

<!-- KPI Gains -->
<div class="mvola-kpi-grid mvola-stagger">
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon green">
            <i class="bi bi-arrow-down-circle"></i>
        </div>
        <div class="mvola-kpi-label">Retraits</div>
        <div class="mvola-kpi-value"><?= number_format((float) $total_retraits, 0, ',', ' ') ?> Ar</div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon blue">
            <i class="bi bi-arrow-left-right"></i>
        </div>
        <div class="mvola-kpi-label">Transferts internes</div>
        <div class="mvola-kpi-value"><?= number_format((float) $total_internes, 0, ',', ' ') ?> Ar</div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon yellow">
            <i class="bi bi-send"></i>
        </div>
        <div class="mvola-kpi-label">Transferts externes</div>
        <div class="mvola-kpi-value"><?= number_format((float) $total_externes, 0, ',', ' ') ?> Ar</div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon yellow">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div class="mvola-kpi-label">Total des gains</div>
        <div class="mvola-kpi-value"><?= number_format((float) $total_gains, 0, ',', ' ') ?> Ar</div>
    </div>
</div>

<!-- Montants dus aux opérateurs externes -->
<div class="mvola-card mb-4">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-arrow-right-circle"></i> Montants dus aux opérateurs externes
        </div>
    </div>
    <div class="mvola-card-body p-0">
        <?php if (empty($montants_dus)) : ?>
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucun transfert externe enregistré.</div>
            </div>
        <?php else : ?>
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
                        <thead>
                            <tr>
                                <th>Opérateur</th>
                                <th class="text-end">Nombre de transferts</th>
                                <th class="text-end">Montant total envoyé</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($montants_dus as $ligne) : ?>
                                <tr>
                                    <td><?= esc($ligne['operateur_externe']) ?></td>
                                    <td class="text-end"><?= (int) $ligne['nombre_operations'] ?></td>
                                    <td class="text-end fw-semibold"><?= number_format((float) $ligne['total_montant'], 0, ',', ' ') ?> Ar</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Détail par type d'opération -->
<div class="mvola-card">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-clock-history"></i> Détail par type d'opération
        </div>
    </div>
    <div class="mvola-card-body p-0">
        <?php if (empty($lignes)) : ?>
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucun gain enregistré pour le moment.</div>
            </div>
        <?php else : ?>
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
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
                            <tr class="mvola-table-tfoot">
                                <th colspan="2" class="text-end">Total général</th>
                                <th class="text-end"><?= number_format((float) $total, 0, ',', ' ') ?> Ar</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>