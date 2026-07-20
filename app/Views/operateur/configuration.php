<?php
$pageTitle  = 'Configuration';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<h1 class="mc-page-title h3"><i class="bi bi-gear"></i> Configuration</h1>

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('operateur/configuration') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="commission_externe" class="form-label">Commission externe (%)</label>
                        <input type="number" class="form-control" id="commission_externe" name="commission_externe"
                               value="<?= (int) $commission_externe ?>" min="0" max="100" required>
                        <div class="form-text">
                            Pourcentage ajouté aux frais de transfert vers les opérateurs externes (Airtel, Orange).
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('operateur/dashboard') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>