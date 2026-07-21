/**
 * Script de diagnostic console pour les modales MVola
 * A executer dans la console du navigateur sur /dashboard
 */

(function diagnosticModales() {
  console.log('%c=== DIAGNOSTIC MODALES MVOLA ===', 'color:#FFD700;font-size:16px;font-weight:bold');

  // 1. Lister toutes les modales Bootstrap
  const modals = document.querySelectorAll('.modal');
  console.log('Modales trouvees:', modals.length);
  modals.forEach(m => {
    const instance = bootstrap.Modal.getInstance(m);
    console.log(' -', m.id, '| classe:', m.className, '| instance Bootstrap:', instance ? 'OUI' : 'NON');
  });

  // 2. Verifier les backdrops
  const backdrops = document.querySelectorAll('.modal-backdrop');
  console.log('Backdrops residuels:', backdrops.length);
  backdrops.forEach(b => console.log(' -', b.className, '| parent:', b.parentElement ? b.parentElement.tagName : 'detache'));

  // 3. Verifier body
  console.log('body.modal-open:', document.body.classList.contains('modal-open'));
  console.log('body.overflow:', getComputedStyle(document.body).overflow);

  // 4. Verifier les elements au centre de l'ecran
  const cx = window.innerWidth / 2;
  const cy = window.innerHeight / 2;
  const topEl = document.elementFromPoint(cx, cy);
  console.log('Element centre (%d, %d):', cx, cy, topEl ? `${topEl.tagName} .${topEl.className}` : 'null');

  // 5. Verifier les event listeners sur les boutons de modales
  console.log('%c--- Event listeners sur boutons de modales ---', 'color:#17A2B8');
  const modalButtons = document.querySelectorAll('#modalDepot button, #modalRetrait button, #modalTransfert button, #modalBulkTransfert button');
  modalButtons.forEach(btn => {
    const listeners = getEventListeners(btn);
    const clickCount = listeners.click ? listeners.click.length : 0;
    console.log('Bouton:', btn.textContent.trim().slice(0, 30), '| click listeners:', clickCount);
  });

  // 6. Verifier les event listeners sur les forms
  console.log('%c--- Event listeners sur formulaires ---', 'color:#17A2B8');
  const forms = document.querySelectorAll('form[data-op]');
  forms.forEach(form => {
    const listeners = getEventListeners(form);
    const submitCount = listeners.submit ? listeners.submit.length : 0;
    console.log('Form:', form.getAttribute('data-op'), '| submit listeners:', submitCount);
  });

  // 7. Tester l'ouverture/fermeture d'une modale
  console.log('%c--- Test automatique modal Depot ---', 'color:#28A745');
  const depotModal = document.getElementById('modalDepot');
  if (depotModal) {
    console.log('Ouverture de modalDepot...');
    const modalInstance = bootstrap.Modal.getOrCreateInstance(depotModal);
    modalInstance.show();

    setTimeout(() => {
      console.log('Etat apres 500ms:');
      const backdropsAfter = document.querySelectorAll('.modal-backdrop').length;
      console.log('Backdrops:', backdropsAfter);
      console.log('body.modal-open:', document.body.classList.contains('modal-open'));

      console.log('Fermeture de modalDepot...');
      modalInstance.hide();

      setTimeout(() => {
        console.log('Etat apres fermeture:');
        const backdropsAfterClose = document.querySelectorAll('.modal-backdrop').length;
        console.log('Backdrops:', backdropsAfterClose);
        console.log('body.modal-open:', document.body.classList.contains('modal-open'));
        console.log('%c=== FIN DIAGNOSTIC ===', 'color:#FFD700;font-size:16px;font-weight:bold');
      }, 600);
    }, 500);
  } else {
    console.log('modalDepot introuvable');
  }
})();
