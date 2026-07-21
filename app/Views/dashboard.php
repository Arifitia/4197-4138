<?php
$pageTitle = 'Tableau de bord';
include __DIR__ . '/partials/header.php';
?>

<div class="mvola-page-header">
    <h1 class="mvola-page-title">Mon tableau de bord</h1>
    <p class="mvola-page-subtitle">Bienvenue, <?= esc(session('client_numero')) ?></p>
</div>

<!-- KPI Cards -->
<div class="mvola-kpi-grid mvola-stagger">
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon yellow">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <div class="mvola-kpi-label">Solde disponible</div>
        <div class="mvola-kpi-value" data-counter="<?= (int) $client['solde'] ?>" data-duration="1500">
            <?= number_format((float) $client['solde'], 0, ',', ' ') ?> Ar
        </div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon green">
            <i class="bi bi-arrow-down-circle"></i>
        </div>
        <div class="mvola-kpi-label">Dépôts</div>
        <div class="mvola-kpi-value">0 Ar</div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon blue">
            <i class="bi bi-arrow-up-circle"></i>
        </div>
        <div class="mvola-kpi-label">Retraits</div>
        <div class="mvola-kpi-value">0 Ar</div>
    </div>
    <div class="mvola-kpi-card">
        <div class="mvola-kpi-icon yellow">
            <i class="bi bi-send"></i>
        </div>
        <div class="mvola-kpi-label">Transferts</div>
        <div class="mvola-kpi-value">0 Ar</div>
    </div>
</div>

<!-- Actions Rapides -->
<div class="mvola-card mb-4">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-lightning-charge"></i> Opérations rapides
        </div>
    </div>
    <div class="mvola-card-body">
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <button type="button" class="mvola-btn mvola-btn-primary mvola-btn-block" data-bs-toggle="modal" data-bs-target="#modalDepot">
                    <i class="bi bi-plus-circle"></i> Dépôt
                </button>
            </div>
            <div class="col-md-3 col-sm-6">
                <button type="button" class="mvola-btn mvola-btn-danger mvola-btn-block" data-bs-toggle="modal" data-bs-target="#modalRetrait">
                    <i class="bi bi-dash-circle"></i> Retrait
                </button>
            </div>
            <div class="col-md-3 col-sm-6">
                <button type="button" class="mvola-btn mvola-btn-secondary mvola-btn-block" data-bs-toggle="modal" data-bs-target="#modalTransfert">
                    <i class="bi bi-send"></i> Transfert
                </button>
            </div>
            <div class="col-md-3 col-sm-6">
                <button type="button" class="mvola-btn mvola-btn-outline mvola-btn-block" data-bs-toggle="modal" data-bs-target="#modalBulkTransfert">
                    <i class="bi bi-broadcast"></i> Transfert Multiple
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Historique -->
<div class="mvola-card">
    <div class="mvola-card-header">
        <div class="card-title">
            <i class="bi bi-clock-history"></i> Historique des opérations
        </div>
    </div>
    <div class="mvola-card-body p-0">
        <?php if (empty($historique)) : ?>
            <div class="mvola-empty">
                <i class="bi bi-inbox"></i>
                <div class="mvola-empty-text">Aucune opération pour le moment.</div>
            </div>
        <?php else : ?>
            <div class="mvola-table-wrapper">
                <div class="table-responsive">
                    <table class="mvola-table">
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
                                        'depot'     => 'mvola-badge-success',
                                        'retrait'   => 'mvola-badge-danger',
                                        'transfert' => 'mvola-badge-info',
                                        default     => 'mvola-badge-secondary',
                                    };
                                    $libelles = ['depot' => 'Dépôt', 'retrait' => 'Retrait', 'transfert' => 'Transfert'];
                                    $libelle  = $libelles[$op['type_operation_nom']] ?? esc($op['type_operation_nom']);
                                ?>
                                <tr>
                                    <td><?= esc(date('d/m/Y H:i', strtotime($op['date_transaction']))) ?></td>
                                    <td><span class="mvola-badge <?= $badgeClass ?>"><?= $libelle ?></span></td>
                                    <td class="text-end"><?= number_format((float) $op['montant'], 0, ',', ' ') ?> Ar</td>
                                    <td class="text-end"><?= number_format((float) $op['frais'], 0, ',', ' ') ?> Ar</td>
                                    <td><?= $op['destinataire_numero'] ? esc($op['destinataire_numero']) : '<span class="mvola-text-muted">-</span>' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modals -->
<!-- Modal Dépôt -->
<div class="modal fade" id="modalDepot" tabindex="-1">
    <div class="modal-dialog">
        <div class="mvola-modal-content">
            <div class="mvola-modal-header">
                <div class="mvola-modal-title"><i class="bi bi-plus-circle text-success"></i> Effectuer un dépôt</div>
                <button type="button" class="mvola-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
                <form data-op="depot">
                    <div class="mvola-modal-body">
                        <div class="mvola-form-group">
                            <label for="montant_depot" class="mvola-form-label">Montant (Ar)</label>
                            <input type="number" min="1" step="1" class="mvola-form-control" id="montant_depot" name="montant" required>
                            <div class="mvola-feedback mt-3"></div>
                        </div>
                    </div>
                    <div class="mvola-modal-footer">
                        <button type="button" class="mvola-btn mvola-btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="mvola-btn mvola-btn-success">Valider le dépôt</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- Modal Retrait -->
<div class="modal fade" id="modalRetrait" tabindex="-1">
    <div class="modal-dialog">
        <div class="mvola-modal-content">
            <div class="mvola-modal-header">
                <div class="mvola-modal-title"><i class="bi bi-dash-circle text-danger"></i> Effectuer un retrait</div>
                <button type="button" class="mvola-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
                <form data-op="retrait">
                    <div class="mvola-modal-body">
                        <div class="mvola-form-group">
                            <label for="montant_retrait" class="mvola-form-label">Montant (Ar)</label>
                            <input type="number" min="1" step="1" class="mvola-form-control" id="montant_retrait" name="montant" required>
                            <div class="mvola-form-text">Des frais seront automatiquement déduits selon le barème en vigueur.</div>
                            <div class="mvola-feedback mt-3"></div>
                        </div>
                    </div>
                    <div class="mvola-modal-footer">
                        <button type="button" class="mvola-btn mvola-btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="mvola-btn mvola-btn-danger">Valider le retrait</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- Modal Transfert -->
<div class="modal fade" id="modalTransfert" tabindex="-1">
    <div class="modal-dialog">
        <div class="mvola-modal-content">
            <div class="mvola-modal-header">
                <div class="mvola-modal-title"><i class="bi bi-send text-primary"></i> Effectuer un transfert</div>
                <button type="button" class="mvola-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
                <form data-op="transfert">
                    <div class="mvola-modal-body">
                        <div class="mvola-form-group">
                            <label for="numero_destinataire" class="mvola-form-label">Numéro du destinataire</label>
                            <input type="tel" class="mvola-form-control mb-3" id="numero_destinataire" name="numero_destinataire" maxlength="10" pattern="\d{10}" required>
                        </div>
                        <div class="mvola-form-group">
                            <label for="montant_transfert" class="mvola-form-label">Montant (Ar)</label>
                            <input type="number" min="1" step="1" class="mvola-form-control mb-3" id="montant_transfert" name="montant" required>
                            <div class="mvola-form-text">Les frais de transfert sont à votre charge et seront déduits de votre solde.</div>
                        </div>
                        <div class="mvola-feedback mt-3"></div>
                    </div>
                    <div class="mvola-modal-footer">
                        <button type="button" class="mvola-btn mvola-btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="mvola-btn mvola-btn-primary">Valider le transfert</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- Modal Transfert Multiple -->
<div class="modal fade" id="modalBulkTransfert" tabindex="-1">
    <div class="modal-dialog">
        <div class="mvola-modal-content">
            <div class="mvola-modal-header">
                <div class="mvola-modal-title"><i class="bi bi-broadcast text-info"></i> Transfert Multiple</div>
                <button type="button" class="mvola-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
                <form data-op="bulkTransfert">
                    <div class="mvola-modal-body">
                        <p class="mvola-text-muted small">Envoyez un montant total à plusieurs destinataires. Le montant sera réparti équitablement entre eux.</p>
                        <div class="mvola-form-group">
                            <label class="mvola-form-label">Numéros des destinataires</label>
                            <div id="numerosContainer" class="mb-3">
                                <div class="input-group mb-2">
                                    <input type="tel" class="mvola-form-control numero-input" maxlength="10" pattern="\d{10}" placeholder="Ex: 0341234567">
                                    <button type="button" class="mvola-btn mvola-btn-outline btn-add-numero" title="Ajouter un numéro">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <div class="input-group mb-2">
                                    <input type="tel" class="mvola-form-control numero-input" maxlength="10" pattern="\d{10}" placeholder="Ex: 0348765432">
                                    <button type="button" class="mvola-btn mvola-btn-danger btn-remove-numero" title="Supprimer ce numéro" style="display:none;">
                                        <i class="bi bi-dash-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mvola-form-group">
                            <label for="montant_bulk_total" class="mvola-form-label">Montant total (Ar)</label>
                            <input type="number" min="1" step="1" class="mvola-form-control mb-3" id="montant_bulk_total" name="montant_total" required>
                        </div>
                        <div class="mvola-alert mvola-alert-info small" id="bulkCalculation" style="display:none;">
                            <strong>Répartition :</strong> <span id="calcText"></span>
                        </div>
                        <div class="mvola-form-text">
                            <i class="bi bi-info-circle"></i> Tous les destinataires doivent être des numéros MVola et être différents.
                        </div>
                        <div class="mvola-feedback mt-3"></div>
                    </div>
                    <div class="mvola-modal-footer">
                        <button type="button" class="mvola-btn mvola-btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="mvola-btn mvola-btn-info">Valider les transferts</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<script>
// ===== Identifiant client et URLs des opérations =====
const MC_CLIENT_ID = <?= (int) session('client_id') ?>;
const MC_URLS = {
    depot: '<?= site_url('operations/depot') ?>',
    retrait: '<?= site_url('operations/retrait') ?>',
    transfert: '<?= site_url('operations/transfert') ?>',
    bulkTransfert: '<?= site_url('operations/bulkTransfert') ?>',
};

// ===== Debug modales client =====
const DEBUG_MODAL = true;
function debugLog(...args) {
  if (DEBUG_MODAL) console.log('%c[MVOLA-MODAL]', 'color:#FFD700;font-weight:bold', ...args);
}

function debugSnapshot(label) {
  const backdrops = document.querySelectorAll('.modal-backdrop');
  const bodyOverflow = getComputedStyle(document.body).overflow;
  const bodyModalOpen = document.body.classList.contains('modal-open');
  const topEl = document.elementFromPoint(window.innerWidth/2, window.innerHeight/2);
  debugLog('SNAPSHOT', label || '', {
    backdrops: backdrops.length,
    bodyModalOpen,
    bodyOverflow,
    topElement: topEl ? topEl.tagName + (topEl.className ? '.' + topEl.className : '') : 'null'
  });
}

// Ecouter tous les clics dans les modales client
document.querySelectorAll('#modalDepot, #modalRetrait, #modalTransfert, #modalBulkTransfert').forEach((modalEl) => {
  modalEl.addEventListener('click', (e) => {
    debugLog('CLICK inside modal', modalEl.id, 'target=', e.target.tagName, e.target.className);
  });

  modalEl.addEventListener('show.bs.modal', () => {
    debugLog('OPEN MODAL', modalEl.id);
    debugSnapshot('after-open');
  });

  modalEl.addEventListener('shown.bs.modal', () => {
    debugLog('SHOWN MODAL', modalEl.id);
    debugSnapshot('after-shown');
  });

  modalEl.addEventListener('hide.bs.modal', () => {
    debugLog('HIDE MODAL', modalEl.id);
  });

  modalEl.addEventListener('hidden.bs.modal', () => {
    debugLog('CLOSE MODAL', modalEl.id);
    setTimeout(() => {
      debugSnapshot('after-hidden');
      const backdrops = document.querySelectorAll('.modal-backdrop');
      if (backdrops.length) {
        backdrops.forEach(b => b.remove());
        debugLog('CLEANUP: backdrops residuels supprimes');
      }
      if (document.body.classList.contains('modal-open')) {
        document.body.classList.remove('modal-open');
        debugLog('CLEANUP: modal-open retire de body');
      }
      document.body.style.overflow = '';
    }, 0);
  });
});

// Logs sur les inputs des modales
document.querySelectorAll('#modalDepot input, #modalRetrait input, #modalTransfert input, #modalBulkTransfert input').forEach((input) => {
  input.addEventListener('focus', () => debugLog('INPUT FOCUS', input.id || input.name));
  input.addEventListener('click', (e) => {
    debugLog('INPUT CLICK', input.id || input.name);
    e.stopPropagation();
  });
});

// Logs sur les boutons des modales
document.querySelectorAll('#modalDepot button, #modalRetrait button, #modalTransfert button, #modalBulkTransfert button').forEach((btn) => {
  btn.addEventListener('click', (e) => {
    debugLog('BUTTON CLICK in modal', btn.textContent.trim(), 'type=', btn.getAttribute('type'), 'dismiss=', btn.getAttribute('data-bs-dismiss'));
  });
});

// ===== Gestion du formulaire de transfert simple =====
const transfertForm = document.querySelector('form[data-op="transfert"]');
const numeroDestinataire = document.getElementById('numero_destinataire');

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
        <input type="tel" class="mvola-form-control numero-input" maxlength="10" pattern="\\d{10}" placeholder="Ex: 0341234567">
        <button type="button" class="mvola-btn mvola-btn-danger btn-remove-numero" title="Supprimer ce numéro">
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
        debugLog('SUBMIT:', form.getAttribute('data-op'));

        const op = form.getAttribute('data-op');
        const feedback = form.querySelector('.mvola-feedback');
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

        feedback.className = 'mvola-feedback mt-3';
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
                feedback.className = 'mvola-feedback mvola-alert-success mt-3';
                feedback.textContent = json.message + ' Nouveau solde : '
                    + Number(json.solde).toLocaleString('fr-FR') + ' Ar.';
                debugLog('SUCCESS:', op, json.message);
                setTimeout(() => window.location.reload(), 1100);
            } else {
                feedback.className = 'mvola-feedback mvola-alert-danger mt-3';
                feedback.textContent = json.message || 'Une erreur est survenue.';
                debugLog('ERROR:', op, json.message);
                submitBtn.disabled = false;
            }
        } catch (err) {
            feedback.className = 'mvola-feedback mvola-alert-danger mt-3';
            feedback.textContent = 'Erreur de connexion au serveur.';
            debugLog('NETWORK ERROR:', err);
            submitBtn.disabled = false;
        }
    });
});

// Initialiser la visibilité des boutons
updateRemoveButtonsVisibility();
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>