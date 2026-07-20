<?php
$pageTitle = 'Historique des opérations';
include __DIR__ . '/partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Historique des opérations</h1>
    <p class="mvola-page-subtitle">Consultez toutes vos transactions</p>
</div>

<div class="mvola-card">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-clock-history"></i> Toutes les opérations
        </div>
    </div>
    <div class="mvola-card-body p-0">
        <?php if (empty($historique)) : ?>
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucune opération pour le moment.</div>
            </div>
        <?php else : ?>
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th class="text-end">Montant</th>
                                <th class="text-end">Frais</th>
                                <th>Destinataire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique as $op) : ?>
                                <?php
                                    $badgeClass = match ($op['type_operation_nom']) {
                                        'depot'     => 'mvola-badge-success',
                                        'retrait'   => 'mvola-badge-danger',
                                        'transfert' => 'mvola-badge-info',
                                        default     => 'mvola-badge-secondary',
                                    };
                                    $libelles = ['depot' => 'Dépôt', 'retrait' => 'Retrait', 'transfert' => 'Transfert'];
                                    $libelle  = $libelles[$op['type_operation_nom']] ?? esc($op['type_operation_nom']);
                                ?>
                                <tr>
                                    <td><?= esc(date('d/m/Y H:i', strtotime($op['date_transaction']))) ?></td>
                                    <td><span class="mvola-badge <?= $badgeClass ?>"><?= $libelle ?></span></td>
                                    <td class="text-end"><?= number_format((float) $op['montant'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end"><?= number_format((float) $op['frais'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= $op['destinataire_numero'] ? esc($op['destinataire_numero']) : '<span class="mvola-text-muted">-</span>' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>