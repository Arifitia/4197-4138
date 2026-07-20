<?php
$isEdit     = isset($type['id']);
$pageTitle  = $isEdit ? "Modifier un type d'opération" : "Ajouter un type d'opération";
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title"><?= $pageTitle ?></h1>
    <p class="mvola-page-subtitle">
        <?php if ($isEdit): ?>
            Modifiez les informations du type d'opération
        <?php else: ?>
            Ajoutez un nouveau type d'opération
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

                <form method="post" action="<?= $isEdit ? site_url('types-operations/update/' . $type['id']) : site_url('types-operations/store') ?>">
                    <?= csrf_field() ?>
                    <div class="mvola-form-group">
                        <label for="nom" class="mvola-form-label">Nom du type d'opération</label>
                        <input type="text" id="nom" name="nom" class="mvola-form-control" maxlength="50" required
                               placeholder="Ex : depot, retrait, transfert"
                               value="<?= esc($type['nom'] ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="mvola-btn mvola-btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('types-operations') ?>" class="mvola-btn mvola-btn-outline">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>