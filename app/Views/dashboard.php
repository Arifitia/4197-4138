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
                <button type="button" class="btn btn-outline-info mc-action-btn" data-bs-toggle="modal" data-bs-target="#modalBulkTransfert">
                    <i class="bi bi-broadcast me-1"></i> Transfert Multiple
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
                    <input type="number" min="1" step="1" class="form-control mb-3" id="montant_transfert" name="montant" required>
                    
                    <div class="form-check mb-3" id="feeCheckContainer" style="display:none;">
                        <input type="checkbox" class="form-check-input" id="payerFraisRetrait" name="payer_frais_retrait" value="1">
                        <label class="form-check-label" for="payerFraisRetrait">
                            <i class="bi bi-info-circle"></i> Inclure les frais de retrait du destinataire
                        </label>
                        <div class="form-text small mt-1">
                            Les frais seront à votre charge. Le destinataire recevra le montant sans frais supplémentaires.
                        </div>
                    </div>
                    
                    <div class="form-text">Les frais de transfert sont à votre charge et seront déduits de votre solde.</div>
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

<!-- Modal Transfert Multiple -->
<div class="modal fade" id="modalBulkTransfert" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-broadcast text-info me-1"></i> Transfert Multiple</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form data-op="bulkTransfert">
                <div class="modal-body">
                    <p class="text-muted small">Envoyez un montant total à plusieurs destinataires. Le montant sera réparti équitablement entre eux.</p>
                    
                    <label class="form-label">Numéros des destinataires</label>
                    <div id="numerosContainer" class="mb-3">
                        <div class="input-group mb-2">
                            <input type="tel" class="form-control numero-input" maxlength="10" pattern="\d{10}" placeholder="Ex: 0341234567">
                            <button type="button" class="btn btn-outline-secondary btn-add-numero" title="Ajouter un numéro">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <div class="input-group mb-2">
                            <input type="tel" class="form-control numero-input" maxlength="10" pattern="\d{10}" placeholder="Ex: 0348765432">
                            <button type="button" class="btn btn-outline-danger btn-remove-numero" title="Supprimer ce numéro" style="display:none;">
                                <i class="bi bi-dash-lg"></i>
                            </button>
                        </div>
                    </div>
                    
                    <label for="montant_bulk_total" class="form-label">Montant total (Ar)</label>
                    <input type="number" min="1" step="1" class="form-control mb-3" id="montant_bulk_total" name="montant_total" required>
                    
                    <div class="alert alert-info small" id="bulkCalculation" style="display:none;">
                        <strong>Répartition :</strong> <span id="calcText"></span>
                    </div>
                    
                    <div class="form-text">
                        <i class="bi bi-info-circle"></i> Tous les destinataires doivent être des numéros MVola et être différents.
                    </div>
                    <div class="mc-feedback mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">Valider les transferts</button>
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
    bulkTransfert: '<?= site_url('operations/bulkTransfert') ?>',
};

// ===== Gestion du formulaire de transfert simple =====
const transfertForm = document.querySelector('form[data-op="transfert"]');
const feeCheckContainer = document.getElementById('feeCheckContainer');
const numeroDestinataire = document.getElementById('numero_destinataire');

// Afficher/masquer le checkbox de frais de retrait selon le numéro
function updateFeeCheckVisibility() {
    const numero = numeroDestinataire.value.trim();
    if (numero.length === 10) {
        // Vérifier si c'est un numéro interne (034, 038)
        const prefix = numero.substring(0, 3);
        const isInternal = ['034', '038'].includes(prefix);
        feeCheckContainer.style.display = isInternal ? 'block' : 'none';
    } else {
        feeCheckContainer.style.display = 'none';
    }
}

numeroDestinataire.addEventListener('input', updateFeeCheckVisibility);
numeroDestinataire.addEventListener('change', updateFeeCheckVisibility);

// ===== Gestion du formulaire de transfert multiple =====
const bulkForm = document.querySelector('form[data-op="bulkTransfert"]');
const numerosContainer = document.getElementById('numerosContainer');
const montantBulkTotal = document.getElementById('montant_bulk_total');
const bulkCalculation = document.getElementById('bulkCalculation');
const calcText = document.getElementById('calcText');

// Ajouter un champ de numéro
function addNumeroField() {
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="tel" class="form-control numero-input" maxlength="10" pattern="\\d{10}" placeholder="Ex: 0341234567">
        <button type="button" class="btn btn-outline-danger btn-remove-numero" title="Supprimer ce numéro">
            <i class="bi bi-dash-lg"></i>
        </button>
    `;
    numerosContainer.appendChild(div);
    
    div.querySelector('.btn-remove-numero').addEventListener('click', (e) => {
        e.preventDefault();
        div.remove();
        updateBulkCalculation();
    });
    
    updateRemoveButtonsVisibility();
    updateBulkCalculation();
}

// Mettre à jour la visibilité des boutons de suppression
function updateRemoveButtonsVisibility() {
    const allInputs = numerosContainer.querySelectorAll('.numero-input');
    numerosContainer.querySelectorAll('.btn-remove-numero').forEach((btn, idx) => {
        btn.style.display = allInputs.length > 2 ? 'block' : 'none';
    });
}

// Calculer la répartition et l'afficher
function updateBulkCalculation() {
    const allInputs = numerosContainer.querySelectorAll('.numero-input');
    const numDestinaires = Array.from(allInputs).filter(input => input.value.trim().length === 10).length;
    const montant = parseInt(montantBulkTotal.value) || 0;
    
    if (numDestinaires >= 2 && montant > 0) {
        const montantParDestinataire = Math.floor(montant / numDestinaires);
        const reste = montant % numDestinaires;
        bulkCalculation.style.display = 'block';
        calcText.textContent = `${numDestinaires} destinataire(s) × ${montantParDestinataire.toLocaleString('fr-FR')} Ar${reste > 0 ? ' + ' + reste + ' Ar au premier' : ''}`;
    } else {
        bulkCalculation.style.display = 'none';
    }
}

// Event listeners pour les champs de numéro
numerosContainer.addEventListener('input', (e) => {
    if (e.target.classList.contains('numero-input')) {
        updateBulkCalculation();
    }
});

numerosContainer.addEventListener('click', (e) => {
    if (e.target.closest('.btn-add-numero')) {
        e.preventDefault();
        addNumeroField();
    }
});

montantBulkTotal.addEventListener('input', updateBulkCalculation);

// ===== Gestion générale des formulaires =====
document.querySelectorAll('form[data-op]').forEach((form) => {
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const op = form.getAttribute('data-op');
        const feedback = form.querySelector('.mc-feedback');
        const submitBtn = form.querySelector('button[type="submit"]');

        const params = new URLSearchParams();
        params.set('client_id', MC_CLIENT_ID);

        // Traitement spécial pour les transferts multiples
        if (op === 'bulkTransfert') {
            const allInputs = form.querySelectorAll('.numero-input');
            const numeros = Array.from(allInputs)
                .map(input => input.value.trim())
                .filter(val => val.length === 10);
            
            params.set('numeros', JSON.stringify(numeros));
            params.set('montant_total', form.querySelector('#montant_bulk_total').value);
        } else {
            // Autres formulaires
            new FormData(form).forEach((value, key) => {
                params.set(key, value);
            });
        }

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

// Initialiser la visibilité des boutons
updateRemoveButtonsVisibility();
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
