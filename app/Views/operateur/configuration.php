<?php
$pageTitle  = 'Configuration';
$activeMenu = 'operateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Configuration</h1>
    <p class="mvola-page-subtitle">Paramètres de l'application</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="mvola-card">
            <div class="mvola-card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="mvola-alert mvola-alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="mvola-alert-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="mvola-alert mvola-alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i><?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="mvola-alert-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('operateur/configuration') ?>">
                    <?= csrf_field() ?>
                    <div class="mvola-form-group">
                        <label for="commission_externe" class="mvola-form-label">Commission externe (%)</label>
                        <input type="number" class="mvola-form-control" id="commission_externe" name="commission_externe"
                               value="<?= (int) $commission_externe ?>" min="0" max="100" required>
                        <div class="mvola-form-text">
                            Pourcentage ajouté aux frais de transfert vers les opérateurs externes (Airtel, Orange).
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="mvola-btn mvola-btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Enregistrer
                        </button>
                        <a href="<?= site_url('operateur/dashboard') ?>" class="mvola-btn mvola-btn-outline">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>