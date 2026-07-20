<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= isset($prefixe['id']) ? 'Modifier' : 'Ajouter' ?> un préfixe</title>
</head>
<body>
    <h1><?= isset($prefixe['id']) ? 'Modifier' : 'Ajouter' ?> un préfixe</h1>

    <?php if (! empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="<?= isset($prefixe['id']) ? site_url('prefixes/update/' . $prefixe['id']) : site_url('prefixes/store') ?>">
        <?= csrf_field() ?>
        <label for="prefixe">Préfixe (3 chiffres, ex: 033)</label>
        <input type="text" id="prefixe" name="prefixe" maxlength="3" required
               value="<?= esc($prefixe['prefixe'] ?? '') ?>">
        <button type="submit">Enregistrer</button>
    </form>

    <a href="<?= site_url('prefixes') ?>">Retour à la liste</a>
</body>
</html>
