<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion opérateur - MVola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/variables.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/login.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="mvola-login-page">
        <div class="mvola-login-container">
            <div class="mvola-login-hero">
                <div class="hero-icon">
                    <i class="bi bi-gear"></i>
                </div>
                <h1>Espace opérateur <span class="mvola-text-gradient">MVola</span></h1>
                <p>Accédez à l'espace de gestion pour consulter les comptes clients, les gains et les configurations.</p>
                <ul class="hero-features">
                    <li><i class="bi bi-check-circle-fill"></i> Gestion des clients</li>
                    <li><i class="bi bi-check-circle-fill"></i> Suivi des gains</li>
                    <li><i class="bi bi-check-circle-fill"></i> Configuration des barèmes</li>
                </ul>
            </div>

            <div class="mvola-login-card-wrapper">
                <div class="mvola-login-card">
                    <div class="card-header">
                        <div class="login-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <h2>Connexion opérateur</h2>
                        <p>Accédez à l'espace de gestion MVola</p>
                    </div>

                    <form method="post" action="<?= site_url('operateur/auth/login') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="mvola-btn mvola-btn-primary mvola-btn-lg mvola-btn-block btn-submit">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </button>
                    </form>

                    <div class="login-footer">
                        <p>Accès réservé au personnel autorisé.</p>
                        <p class="mt-2">
                            <a href="<?= site_url('auth') ?>">Espace client</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>