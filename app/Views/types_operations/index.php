<?php
$pageTitle  = "Types d'opérations";
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Types d'opérations</h1>
    <p class="mvola-page-subtitle">Gestion des types d'opérations disponibles</p>
</div>

<div class="mvola-card">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-list-check"></i> Liste des types
        </div>
        <a href="<?= site_url('types-operations/create') ?>" class="mvola-btn mvola-btn-primary mvola-btn-sm">
            <i class="bi bi-plus-lg"></i> Ajouter un type
        </a>
    </div>
    <div class="mvola-card-body p-0">
        <?php if (empty($types)) : ?>
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucun type d'opération enregistré.</div>
            </div>
        <?php else : ?>
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $t) : ?>
                                <tr>
                                    <td><?= esc($t['id']) ?></td>
                                    <td><?= esc(ucfirst($t['nom'])) ?></td>
                                    <td class="text-end">
                                        <a href="<?= site_url('types-operations/edit/' . $t['id']) ?>" class="mvola-btn mvola-btn-outline mvola-btn-sm">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <a href="<?= site_url('types-operations/delete/' . $t['id']) ?>"
                                           class="mvola-btn mvola-btn-danger mvola-btn-sm"
                                           onclick="return confirm('Supprimer ce type d\'opération ?')">
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