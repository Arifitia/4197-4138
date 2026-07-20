<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Préfixes opérateur</title>
</head>
<body>
    <h1>Préfixes opérateur</h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <p><?= esc(session()->getFlashdata('success')) ?></p>
    <?php endif; ?>

    <a href="<?= site_url('prefixes/create') ?>">Ajouter un préfixe</a>

    <table border="1" cellpadding="6">
        <tr>
            <th>Préfixe</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($prefixes as $p) : ?>
        <tr>
            <td><?= esc($p['prefixe']) ?></td>
            <td>
                <a href="<?= site_url('prefixes/edit/' . $p['id']) ?>">Modifier</a>
                <a href="<?= site_url('prefixes/delete/' . $p['id']) ?>" onclick="return confirm('Supprimer ce préfixe ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
