<?php
$pageTitle  = 'Connexion';
$activeMenu = 'client';
include __DIR__ . '/partials/header.php';
?>

<div class="mc-login-wrapper">
    <div class="row justify-content-center w-100">
        <div class="col-12 col-sm-9 col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <div class="mc-login-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h1 class="h4 text-center mc-page-title">Connexion par téléphone</h1>
                    <p class="text-center text-muted mb-4">
                        Entrez votre numéro. Si c'est votre première visite, un compte est créé automatiquement.
                    </p>

                    <form method="post" action="<?= site_url('auth/login') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="numero_telephone" class="form-label">Numéro de téléphone</label>
                            <input
                                type="tel"
                                class="form-control form-control-lg"
                                id="numero_telephone"
                                name="numero_telephone"
                                placeholder="Ex : 0331234567"
                                maxlength="10"
                                pattern="\d{10}"
                                inputmode="numeric"
                                autofocus
                                required
                            >
                            <div class="form-text">10 chiffres, en commençant par un préfixe opérateur valide (033, 034, 032...).</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
