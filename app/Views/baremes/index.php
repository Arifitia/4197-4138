<?php
$pageTitle  = 'Barèmes de frais';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';

$libelles = ['depot' => 'Dépôt', 'retrait' => 'Retrait', 'transfert' => 'Transfert'];
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Barèmes de frais</h1>
    <p class="mvola-page-subtitle">Gestion des tranches de frais par type d'opération</p>
</div>

<div class="mvola-card">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-cash-coin"></i> Liste des barèmes
        </div>
        <a href="<?= site_url('baremes/create') ?>" class="mvola-btn mvola-btn-primary mvola-btn-sm">
            <i class="bi bi-plus-lg"></i> Ajouter une tranche
        </a>
    </div>
    <div class="mvola-card-body p-0">
        <?php if (empty($baremes)) : ?>
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucun barème enregistré.</div>
            </div>
        <?php else : ?>
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
                        <thead>
                            <tr>
                                <th>Type d'opération</th>
                                <th class="text-end">Montant min</th>
                                <th class="text-end">Montant max</th>
                                <th class="text-end">Frais</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($baremes as $b) : ?>
                                <tr>
                                    <td><?= esc($libelles[$b['type_operation_nom']] ?? $b['type_operation_nom']) ?></td>
                                    <td class="text-end"><?= number_format((float) $b['montant_min'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end"><?= number_format((float) $b['montant_max'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end fw-semibold"><?= number_format((float) $b['frais'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end">
                                        <a href="<?= site_url('baremes/edit/' . $b['id']) ?>" class="mvola-btn mvola-btn-outline mvola-btn-sm">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <a href="<?= site_url('baremes/delete/' . $b['id']) ?>"
                                           class="mvola-btn mvola-btn-danger mvola-btn-sm"
                                           onclick="return confirm('Supprimer cette tranche ?')">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </a>
                                    </td>
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