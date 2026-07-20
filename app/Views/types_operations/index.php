<?php
$pageTitle  = "Types d'opérations";
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="mc-page-title h3 mb-0"><i class="bi bi-list-check"></i> Types d'opérations</h1>
    <a href="<?= site_url('types-operations/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter un type
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($types)) : ?>
            <div class="mc-empty">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Aucun type d'opération enregistré.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
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
                                <a href="<?= site_url('types-operations/edit/' . $t['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <a href="<?= site_url('types-operations/delete/' . $t['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Supprimer ce type d\'opération ?')">
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
