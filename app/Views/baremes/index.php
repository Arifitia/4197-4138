<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Barèmes de frais</title>
</head>
<body>
    <h1>Barèmes de frais</h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <p><?= esc(session()->getFlashdata('success')) ?></p>
    <?php endif; ?>

    <a href="<?= site_url('baremes/create') ?>">Ajouter une tranche</a>

    <table border="1" cellpadding="6">
        <tr>
            <th>Type d'opération</th>
            <th>Montant min</th>
            <th>Montant max</th>
            <th>Frais</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($baremes as $b) : ?>
        <tr>
            <td><?= esc($b['type_operation_nom']) ?></td>
            <td><?= esc($b['montant_min']) ?></td>
            <td><?= esc($b['montant_max']) ?></td>
            <td><?= esc($b['frais']) ?></td>
            <td>
                <a href="<?= site_url('baremes/edit/' . $b['id']) ?>">Modifier</a>
                <a href="<?= site_url('baremes/delete/' . $b['id']) ?>" onclick="return confirm('Supprimer cette tranche ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
