/**
 * MVola - Application JavaScript
 * Animations et interactions
 */

document.addEventListener('DOMContentLoaded', function () {
  // ============================================
  // SIDEBAR MOBILE
  // ============================================
  const sidebarToggle = document.querySelector('.mvola-sidebar-toggle');
  const sidebar = document.querySelector('.mvola-sidebar');
  const sidebarOverlay = document.querySelector('.mvola-sidebar-overlay');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('show');
      if (sidebarOverlay) {
        sidebarOverlay.classList.toggle('show');
      }
    });
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', function () {
      sidebar.classList.remove('show');
      sidebarOverlay.classList.remove('show');
    });
  }

  // Close sidebar on escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
      sidebar.classList.remove('show');
      if (sidebarOverlay) {
        sidebarOverlay.classList.remove('show');
      }
    }
  });

  // ============================================
  // ANIMATED COUNTERS
  // ============================================
  const counters = document.querySelectorAll('[data-counter]');

  counters.forEach(function (counter) {
    const target = parseInt(counter.getAttribute('data-counter'), 10);
    const duration = parseInt(counter.getAttribute('data-duration') || '1500', 10);
    const start = 0;
    const startTime = performance.now();

    function updateCounter(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Ease out cubic
      const easeOut = 1 - Math.pow(1 - progress, 3);
      const current = Math.floor(start + (target - start) * easeOut);

      counter.textContent = current.toLocaleString('fr-FR');

      if (progress < 1) {
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = target.toLocaleString('fr-FR');
      }
    }

    requestAnimationFrame(updateCounter);
  });

  // ============================================
  // FADE IN ON SCROLL
  // ============================================
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px',
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('mvola-animate-fade-in-up');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.mvola-card, .mvola-kpi-card').forEach(function (el) {
    observer.observe(el);
  });

  // ============================================
  // RIPPLE EFFECT ON BUTTONS
  // ============================================
  document.querySelectorAll('.mvola-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      const rect = btn.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top - size / 2;

      const ripple = document.createElement('span');
      ripple.style.cssText = [
        'position: absolute',
        'border-radius: 50%',
        'background: rgba(255, 255, 255, 0.35)',
        'width: ' + size + 'px',
        'height: ' + size + 'px',
        'left: ' + x + 'px',
        'top: ' + y + 'px',
        'pointer-events: none',
        'animation: ripple 0.6s ease-out forwards',
      ].join(';');

      btn.appendChild(ripple);

      setTimeout(function () {
        ripple.remove();
      }, 600);
    });
  });

  // ============================================
  // SMOOTH SCROLL FOR ANCHOR LINKS
  // ============================================
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;

      const target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start',
        });
      }
    });
  });

  // ============================================
  // AUTO DISMISS ALERTS
  // ============================================
  const alerts = document.querySelectorAll('.mvola-alert');
  alerts.forEach(function (alert) {
    setTimeout(function () {
      const bsAlert = bootstrap.Alert ? bootstrap.Alert.getOrCreateInstance(alert) : null;
      if (bsAlert) {
        bsAlert.close();
      } else {
        alert.style.transition = 'opacity 0.3s ease';
        alert.style.opacity = '0';
        setTimeout(function () {
          alert.remove();
        }, 300);
      }
    }, 4000);
  });
});
