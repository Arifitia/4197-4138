<?php
$pageTitle  = 'Préfixes opérateur';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h1 class="mc-page-title h3 mb-0"><i class="bi bi-hash"></i> Préfixes opérateur</h1>
    <a href="<?= site_url('prefixes/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter un préfixe
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($prefixes)) : ?>
            <div class="mc-empty">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Aucun préfixe enregistré.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Préfixe</th>
                            <th>Opérateur</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prefixes as $p) : ?>
                            <?php
                                $isMvola = $p['operateur_code'] === 'MVOLA';
                                $badgeClass = match ($p['type']) {
                                    'interne' => 'bg-success',
                                    'externe' => 'bg-secondary',
                                    default => 'bg-secondary',
                                };
                            ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($p['prefixe']) ?></td>
                                <td><?= esc($p['operateur_code']) ?></td>
                                <td><span class="badge <?= $badgeClass ?>"><?= esc($p['type']) ?></span></td>
                                <td class="text-end">
                                    <?php if (! $isMvola) : ?>
                                        <a href="<?= site_url('prefixes/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <a href="<?= site_url('prefixes/delete/' . $p['id']) ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Supprimer ce préfixe ?')">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted small">Protégé</span>
                                    <?php endif; ?>
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
