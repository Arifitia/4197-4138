<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center d-flex justify-content-between align-items-center">
                    <span>Tableau de bord</span>
                    <a href="<?= site_url('dashboard/logout') ?>" class="btn btn-sm btn-outline-danger">Déconnexion</a>
                </div>
                <div class="card-body text-center">
                    <p class="mb-1">Numéro : <strong><?= esc($client['phone_number']) ?></strong></p>
                    <h2 class="mt-3">Solde : <?= number_format($client['solde'], 0, ',', ' ') ?> Ar</h2>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>