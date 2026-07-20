<?php
$pageTitle = 'Connexion opérateur';
include __DIR__ . '/../partials/header.php';
?>

<div class="row justify-content-center w-100">
    <div class="col-12 col-sm-10 col-md-6 col-lg-5">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <div class="mc-login-icon">
                    <i class="bi bi-building"></i>
                </div>
                <h1 class="h4 text-center mc-page-title">Espace opérateur MVola</h1>
                <p class="text-center text-muted mb-4">
                    Accédez à l'espace de gestion MVola.
                </p>

                <form method="post" action="<?= site_url('operateur/auth/login') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>