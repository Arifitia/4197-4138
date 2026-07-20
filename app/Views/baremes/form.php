<?php
$isEdit     = isset($bareme['id']);
$pageTitle  = $isEdit ? 'Modifier une tranche' : 'Ajouter une tranche';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title"><?= $pageTitle ?></h1>
    <p class="mvola-page-subtitle">
        <?php if ($isEdit): ?>
            Modifiez les paramètres de cette tranche
        <?php else: ?>
            Définissez un nouveau barème de frais
        <?php endif; ?>
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-7">
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

                <form method="post" action="<?= $isEdit ? site_url('baremes/update/' . $bareme['id']) : site_url('baremes/store') ?>">
                    <?= csrf_field() ?>

                    <div class="mvola-form-group">
                        <label for="type_operation_id" class="mvola-form-label">Type d'opération</label>
                        <select id="type_operation_id" name="type_operation_id" class="mvola-form-control mvola-form-select" required>
                            <?php foreach ($types as $t) : ?>
                                <option value="<?= esc($t['id']) ?>" <?= (($bareme['type_operation_id'] ?? null) == $t['id']) ? 'selected' : '' ?>>
                                    <?= esc(ucfirst($t['nom'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="montant_min" class="mvola-form-label">Montant min</label>
                            <input type="number" id="montant_min" name="montant_min" class="mvola-form-control" required
                                   value="<?= esc($bareme['montant_min'] ?? '') ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="montant_max" class="mvola-form-label">Montant max</label>
                            <input type="number" id="montant_max" name="montant_max" class="mvola-form-control" required
                                   value="<?= esc($bareme['montant_max'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mvola-form-group">
                        <label for="frais" class="mvola-form-label">Frais</label>
                        <input type="number" id="frais" name="frais" class="mvola-form-control" required
                               value="<?= esc($bareme['frais'] ?? '') ?>">
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="mvola-btn mvola-btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('baremes') ?>" class="mvola-btn mvola-btn-outline">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>