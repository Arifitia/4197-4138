<?php
$isEdit     = isset($type['id']);
$pageTitle  = $isEdit ? "Modifier un type d'opération" : "Ajouter un type d'opération";
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

                <form method="post" action="<?= $isEdit ? site_url('types-operations/update/' . $type['id']) : site_url('types-operations/store') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du type d'opération</label>
                        <input type="text" id="nom" name="nom" class="form-control" maxlength="50" required
                               placeholder="Ex : depot, retrait, transfert"
                               value="<?= esc($type['nom'] ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('types-operations') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
