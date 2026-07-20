<?php
$isEdit     = isset($prefixe['id']);
$pageTitle  = $isEdit ? 'Modifier un préfixe' : 'Ajouter un préfixe';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title"><?= $pageTitle ?></h1>
    <p class="mvola-page-subtitle">
        <?php if ($isEdit): ?>
            Modifiez les informations du préfixe
        <?php else: ?>
            Ajoutez un nouveau préfixe à la liste
        <?php endif; ?>
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="mvola-card">
            <div class="mvola-card-body">
                <?php if (! empty($errors)) : ?>
                    <div class="mvola-alert mvola-alert-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <div>
                            <strong>Erreurs de validation</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= $isEdit ? site_url('prefixes/update/' . $prefixe['id']) : site_url('prefixes/store') ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="prefixe" class="form-label">Préfixe (3 chiffres, ex: 033)</label>
                        <input type="text" id="prefixe" name="prefixe" class="form-control" maxlength="3" required
                               value="<?= esc($prefixe['prefixe'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="operateur_code" class="form-label">Opérateur</label>
                        <select id="operateur_code" name="operateur_code" class="form-control mvola-form-select" required>
                            <option value="">-- Choisir --</option>
                            <option value="MVOLA" <?= (($prefixe['operateur_code'] ?? '') === 'MVOLA') ? 'selected' : '' ?>>MVola</option>
                            <option value="AIRTEL" <?= (($prefixe['operateur_code'] ?? '') === 'AIRTEL') ? 'selected' : '' ?>>Airtel</option>
                            <option value="ORANGE" <?= (($prefixe['operateur_code'] ?? '') === 'ORANGE') ? 'selected' : '' ?>>Orange</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-control mvola-form-select" required>
                            <option value="">-- Choisir --</option>
                            <option value="interne" <?= (($prefixe['type'] ?? '') === 'interne') ? 'selected' : '' ?>>Interne</option>
                            <option value="externe" <?= (($prefixe['type'] ?? '') === 'externe') ? 'selected' : '' ?>>Externe</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="mvola-btn mvola-btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('prefixes') ?>" class="mvola-btn mvola-btn-outline">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>