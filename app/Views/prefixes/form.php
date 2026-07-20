<?php
$isEdit     = isset($prefixe['id']);
$pageTitle  = $isEdit ? 'Modifier un préfixe' : 'Ajouter un préfixe';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<h1 class="mc-page-title h3">
    <i class="bi bi-<?= $isEdit ? 'pencil' : 'plus-lg' ?>"></i> <?= $pageTitle ?>
</h1>

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <?php if (! empty($errors)) : ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= $isEdit ? site_url('prefixes/update/' . $prefixe['id']) : site_url('prefixes/store') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="prefixe" class="form-label">Préfixe (3 chiffres, ex: 033)</label>
                        <input type="text" id="prefixe" name="prefixe" class="form-control" maxlength="3" required
                               value="<?= esc($prefixe['prefixe'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="operateur_code" class="form-label">Opérateur</label>
                        <select id="operateur_code" name="operateur_code" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <option value="MVOLA" <?= (($prefixe['operateur_code'] ?? '') === 'MVOLA') ? 'selected' : '' ?>>MVola</option>
                            <option value="AIRTEL" <?= (($prefixe['operateur_code'] ?? '') === 'AIRTEL') ? 'selected' : '' ?>>Airtel</option>
                            <option value="ORANGE" <?= (($prefixe['operateur_code'] ?? '') === 'ORANGE') ? 'selected' : '' ?>>Orange</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <option value="interne" <?= (($prefixe['type'] ?? '') === 'interne') ? 'selected' : '' ?>>Interne</option>
                            <option value="externe" <?= (($prefixe['type'] ?? '') === 'externe') ? 'selected' : '' ?>>Externe</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('prefixes') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
