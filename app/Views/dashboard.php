<?php
$pageTitle  = 'Tableau de bord';
$activeMenu = 'client';
include __DIR__ . '/partials/header.php';
?>

<h1 class="mc-page-title h3"><i class="bi bi-speedometer2"></i> Mon tableau de bord</h1>

<div class="row g-4">
    <!-- Solde + infos client -->
    <div class="col-12 col-lg-4">
        <div class="card mc-solde-card mb-4">
            <div class="card-body p-4">
                <div class="mc-solde-label">Solde disponible</div>
                <div class="mc-solde-amount"><?= number_format((float) $client['solde'], 0, ',', ' ') ?> Ar</div>
                <hr class="border-light opacity-25">
                <div><i class="bi bi-telephone me-1"></i> <?= esc($client['numero_telephone']) ?></div>
                <div class="small opacity-75 mt-1">
                    Client depuis le <?= esc(date('d/m/Y', strtotime($client['date_creation']))) ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">Opérations rapides</div>
            <div class="card-body d-grid gap-2">
                <button type="button" class="btn btn-outline-success mc-action-btn" data-bs-toggle="modal" data-bs-target="#modalDepot">
                    <i class="bi bi-plus-circle me-1"></i> Dépôt
                </button>
                <button type="button" class="btn btn-outline-danger mc-action-btn" data-bs-toggle="modal" data-bs-target="#modalRetrait">
                    <i class="bi bi-dash-circle me-1"></i> Retrait
                </button>
                <button type="button" class="btn btn-outline-primary mc-action-btn" data-bs-toggle="modal" data-bs-target="#modalTransfert">
                    <i class="bi bi-send me-1"></i> Transfert
                </button>
            </div>
        </div>
    </div>

    <!-- Historique -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <i class="bi bi-clock-history me-1"></i> Historique des opérations
            </div>
            <div class="card-body p-0">
                <?php if (empty($historique)) : ?>
                    <div class="mc-empty">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Aucune opération pour le moment.
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">Frais</th>
                                    <th>Destinataire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique as $op) : ?>
                                    <?php
                                        $badgeClass = match ($op['type_operation_nom']) {
                                            'depot'     => 'badge-op-depot',
                                            'retrait'   => 'badge-op-retrait',
                                            'transfert' => 'badge-op-transfert',
                                            default     => 'bg-secondary',
                                        };
                                        $libelles = ['depot' => 'Dépôt', 'retrait' => 'Retrait', 'transfert' => 'Transfert'];
                                        $libelle  = $libelles[$op['type_operation_nom']] ?? esc($op['type_operation_nom']);
                                    ?>
                                    <tr>
                                        <td><?= esc(date('d/m/Y H:i', strtotime($op['date_transaction']))) ?></td>
                                        <td><span class="badge <?= $badgeClass ?>"><?= $libelle ?></span></td>
                                        <td class="text-end"><?= number_format((float) $op['montant'], 0, ',', ' ') ?> Ar</td>
                                        <td class="text-end"><?= number_format((float) $op['frais'], 0, ',', ' ') ?> Ar</td>
                                        <td><?= $op['destinataire_numero'] ? esc($op['destinataire_numero']) : '<span class="text-muted">-</span>' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dépôt -->
<div class="modal fade" id="modalDepot" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle text-success me-1"></i> Effectuer un dépôt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-op="depot">
                <div class="modal-body">
                    <label for="montant_depot" class="form-label">Montant (Ar)</label>
                    <input type="number" min="1" step="1" class="form-control" id="montant_depot" name="montant" required>
                    <div class="mc-feedback mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider le dépôt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Retrait -->
<div class="modal fade" id="modalRetrait" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-dash-circle text-danger me-1"></i> Effectuer un retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-op="retrait">
                <div class="modal-body">
                    <label for="montant_retrait" class="form-label">Montant (Ar)</label>
                    <input type="number" min="1" step="1" class="form-control" id="montant_retrait" name="montant" required>
                    <div class="form-text">Des frais seront automatiquement déduits selon le barème en vigueur.</div>
                    <div class="mc-feedback mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Valider le retrait</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Transfert -->
<div class="modal fade" id="modalTransfert" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-send text-primary me-1"></i> Effectuer un transfert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-op="transfert">
                <div class="modal-body">
                    <label for="numero_destinataire" class="form-label">Numéro du destinataire</label>
                    <input type="tel" class="form-control mb-3" id="numero_destinataire" name="numero_destinataire" maxlength="10" pattern="\d{10}" required>
                    <label for="montant_transfert" class="form-label">Montant (Ar)</label>
                    <input type="number" min="1" step="1" class="form-control" id="montant_transfert" name="montant" required>
                    <div class="form-text">Les frais sont à votre charge et seront déduits de votre solde.</div>
                    <div class="mc-feedback mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider le transfert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const MC_CLIENT_ID = <?= (int) $client['id'] ?>;
const MC_URLS = {
    depot: '<?= site_url('operations/depot') ?>',
    retrait: '<?= site_url('operations/retrait') ?>',
    transfert: '<?= site_url('operations/transfert') ?>',
};

document.querySelectorAll('form[data-op]').forEach((form) => {
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const op = form.getAttribute('data-op');
        const feedback = form.querySelector('.mc-feedback');
        const submitBtn = form.querySelector('button[type="submit"]');

        const params = new URLSearchParams(new FormData(form));
        params.set('client_id', MC_CLIENT_ID);

        feedback.className = 'mc-feedback mt-3';
        feedback.textContent = '';
        submitBtn.disabled = true;

        try {
            const res = await fetch(MC_URLS[op], {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params,
            });
            const json = await res.json();

            if (json.success) {
                feedback.className = 'mc-feedback alert alert-success mt-3';
                feedback.textContent = json.message + ' Nouveau solde : '
                    + Number(json.solde).toLocaleString('fr-FR') + ' Ar.';
                setTimeout(() => window.location.reload(), 1100);
            } else {
                feedback.className = 'mc-feedback alert alert-danger mt-3';
                feedback.textContent = json.message || 'Une erreur est survenue.';
                submitBtn.disabled = false;
            }
        } catch (err) {
            feedback.className = 'mc-feedback alert alert-danger mt-3';
            feedback.textContent = 'Erreur de connexion au serveur.';
            submitBtn.disabled = false;
        }
    });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
