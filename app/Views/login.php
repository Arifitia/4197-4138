<!DOCTYPE html>
<html>
<head>
    <title>Login Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header text-center">Connexion par Téléphone</div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>
                    <form method="post" action="<?= site_url('auth/login') ?>">
                        <div class="mb-3">
                            <label>Numéro de téléphone (ex : 0331234567)</label>
                            <input type="text" class="form-control" name="phone_number" required maxlength="10">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>