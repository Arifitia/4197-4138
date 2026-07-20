<?php
$isEdit     = isset($bareme['id']);
$pageTitle  = $isEdit ? 'Modifier une tranche' : 'Ajouter une tranche';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<h1 class="mc-page-title h3">
    <i class="bi bi-<?= $isEdit ? 'pencil' : 'plus-lg' ?>"></i> <?= $pageTitle ?>
</h1>

<div class="row justify-content-center">
    <div class="col-12 col-md-7">
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

                <form method="post" action="<?= $isEdit ? site_url('baremes/update/' . $bareme['id']) : site_url('baremes/store') ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="type_operation_id" class="form-label">Type d'opération</label>
                        <select id="type_operation_id" name="type_operation_id" class="form-select" required>
                            <?php foreach ($types as $t) : ?>
                                <option value="<?= esc($t['id']) ?>" <?= (($bareme['type_operation_id'] ?? null) == $t['id']) ? 'selected' : '' ?>>
                                    <?= esc(ucfirst($t['nom'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="montant_min" class="form-label">Montant min</label>
                            <input type="number" id="montant_min" name="montant_min" class="form-control" required
                                   value="<?= esc($bareme['montant_min'] ?? '') ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="montant_max" class="form-label">Montant max</label>
                            <input type="number" id="montant_max" name="montant_max" class="form-control" required
                                   value="<?= esc($bareme['montant_max'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="frais" class="form-label">Frais</label>
                        <input type="number" id="frais" name="frais" class="form-control" required
                               value="<?= esc($bareme['frais'] ?? '') ?>">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('baremes') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
