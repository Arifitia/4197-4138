<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion client - MVola</title>
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
                    <i class="bi bi-wallet2"></i>
                </div>
                <h1>Bienvenue dans votre espace <span class="mvola-text-gradient">MVola</span></h1>
                <p>Accédez à votre compte en toute sécurité. Gérez vos dépôts, retraits et transferts en quelques clics.</p>
                <ul class="hero-features">
                    <li><i class="bi bi-check-circle-fill"></i> Transactions sécurisées</li>
                    <li><i class="bi bi-check-circle-fill"></i> Accès 24h/24</li>
                    <li><i class="bi bi-check-circle-fill"></i> Frais transparents</li>
                </ul>
            </div>

            <div class="mvola-login-card-wrapper">
                <div class="mvola-login-card">
                    <div class="card-header">
                        <div class="login-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <h2>Connexion</h2>
                        <p>Entrez votre numéro MVola pour vous connecter</p>
                    </div>

                    <form method="post" action="<?= site_url('auth/login') ?>">
                        <?= csrf_field() ?>
                        <div class="mvola-form-group">
                            <label for="numero_telephone" class="mvola-form-label">Numéro de téléphone</label>
                            <input
                                type="tel"
                                class="mvola-form-control"
                                id="numero_telephone"
                                name="numero_telephone"
                                placeholder="Ex : 0341234567"
                                maxlength="10"
                                pattern="\d{10}"
                                inputmode="numeric"
                                autofocus
                                required
                            >
                            <div class="mvola-form-text">Numéros MVola uniquement : 034 ou 038.</div>
                        </div>
                        <button type="submit" class="mvola-btn mvola-btn-primary mvola-btn-lg mvola-btn-block btn-submit">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </button>
                    </form>

                    <div class="login-footer">
                        <p>Première visite ? Un compte sera créé automatiquement.</p>
                        <div class="mt-3">
                            <a href="<?= site_url('operateur/auth') ?>" class="mvola-btn mvola-btn-outline mvola-btn-block">
                                <i class="bi bi-building"></i> Espace opérateur
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>