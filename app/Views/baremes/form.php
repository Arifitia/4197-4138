<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= isset($bareme['id']) ? 'Modifier' : 'Ajouter' ?> une tranche</title>
</head>
<body>
    <h1><?= isset($bareme['id']) ? 'Modifier' : 'Ajouter' ?> une tranche</h1>

    <?php if (! empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="<?= isset($bareme['id']) ? site_url('baremes/update/' . $bareme['id']) : site_url('baremes/store') ?>">
        <?= csrf_field() ?>

        <label for="type_operation_id">Type d'opération</label>
        <select id="type_operation_id" name="type_operation_id" required>
            <?php foreach ($types as $t) : ?>
                <option value="<?= esc($t['id']) ?>" <?= (($bareme['type_operation_id'] ?? null) == $t['id']) ? 'selected' : '' ?>>
                    <?= esc($t['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="montant_min">Montant min</label>
        <input type="number" id="montant_min" name="montant_min" required
               value="<?= esc($bareme['montant_min'] ?? '') ?>">

        <label for="montant_max">Montant max</label>
        <input type="number" id="montant_max" name="montant_max" required
               value="<?= esc($bareme['montant_max'] ?? '') ?>">

        <label for="frais">Frais</label>
        <input type="number" id="frais" name="frais" required
               value="<?= esc($bareme['frais'] ?? '') ?>">

        <button type="submit">Enregistrer</button>
    </form>

    <a href="<?= site_url('baremes') ?>">Retour à la liste</a>
</body>
</html>
