<?php
$pageTitle  = 'Barèmes de frais';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';

$libelles = ['depot' => 'Dépôt', 'retrait' => 'Retrait', 'transfert' => 'Transfert'];
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="mc-page-title h3 mb-0"><i class="bi bi-cash-coin"></i> Barèmes de frais</h1>
    <a href="<?= site_url('baremes/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter une tranche
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($baremes)) : ?>
            <div class="mc-empty">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Aucun barème enregistré.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
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
                                <a href="<?= site_url('baremes/edit/' . $b['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <a href="<?= site_url('baremes/delete/' . $b['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Supprimer cette tranche ?')">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
