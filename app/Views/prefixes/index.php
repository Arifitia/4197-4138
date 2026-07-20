<?php
$pageTitle = 'Préfixes opérateur';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Préfixes opérateur</h1>
    <p class="mvola-page-subtitle">Gestion des préfixes téléphoniques et identification des opérateurs</p>
</div>

<?php if (!empty($prefixes)): ?>
    <div class="mvola-card">
        <div class="mvola-card-header">
            <div class="card-title">
                <i class="bi bi-hash"></i> Liste des préfixes
            </div>
            <a href="<?= site_url('prefixes/create') ?>" class="mvola-btn mvola-btn-primary mvola-btn-sm">
                <i class="bi bi-plus-lg"></i> Ajouter
            </a>
        </div>
        <div class="mvola-card-body p-0">
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
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
                                        'interne' => 'mvola-badge-success',
                                        'externe' => 'mvola-badge-secondary',
                                        default => 'mvola-badge-secondary',
                                    };
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($p['prefixe']) ?></td>
                                    <td><?= esc($p['operateur_code']) ?></td>
                                    <td><span class="mvola-badge <?= $badgeClass ?>"><?= esc($p['type']) ?></span></td>
                                    <td class="text-end">
                                        <?php if (! $isMvola) : ?>
                                            <a href="<?= site_url('prefixes/edit/' . $p['id']) ?>" class="mvola-btn mvola-btn-outline mvola-btn-sm">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <a href="<?= site_url('prefixes/delete/' . $p['id']) ?>"
                                               class="mvola-btn mvola-btn-danger mvola-btn-sm"
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
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="mvola-card">
        <div class="mvola-card-body">
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucun préfixe enregistré.</div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>