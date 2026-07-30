/* ═══════════════════════════════════════════
   SobatMedis — app.js
   Carousel, Sidebar Toggle, Modal, Scroll Animations
   ═══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {

  /* ─── Carousel ─── */
  document.querySelectorAll('.carousel-section').forEach(function (section) {
    const track = section.querySelector('.carousel-track');
    const prevBtn = section.querySelector('.carousel-btn-prev');
    const nextBtn = section.querySelector('.carousel-btn-next');
    if (!track || !prevBtn || !nextBtn) return;

    let offset = 0;
    const cardWidth = 276; // 260 + 16 gap

    nextBtn.addEventListener('click', function () {
      const maxOffset = track.scrollWidth - track.parentElement.offsetWidth;
      offset = Math.min(offset + cardWidth, maxOffset);
      track.style.transform = 'translateX(-' + offset + 'px)';
    });

    prevBtn.addEventListener('click', function () {
      offset = Math.max(offset - cardWidth, 0);
      track.style.transform = 'translateX(-' + offset + 'px)';
    });
  });

  /* ─── Sidebar Toggle (Mobile) ─── */
  var sidebarToggle = document.getElementById('sidebar-toggle');
  var sidebar = document.getElementById('sidebar');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
      if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  }

  /* ─── Modal ─── */
  document.querySelectorAll('[data-modal-target]').forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      var target = document.querySelector(this.dataset.modalTarget);
      if (target) target.classList.add('active');
    });
  });

  document.querySelectorAll('.modal-close, [data-modal-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var overlay = this.closest('.modal-overlay');
      if (overlay) overlay.classList.remove('active');
    });
  });

  document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) overlay.classList.remove('active');
    });
  });

  /* ─── Scroll Animations ─── */
  var animateElements = document.querySelectorAll('.animate-on-scroll');
  if (animateElements.length > 0) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, { threshold: 0.1 });

    animateElements.forEach(function (el) {
      el.style.transform = 'translateY(20px)';
      observer.observe(el);
    });
  }

  /* ─── Delete Confirmation ─── */
  document.querySelectorAll('[data-confirm]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      if (!confirm(this.dataset.confirm)) {
        e.preventDefault();
      }
    });
  });

  /* ─── Password Toggle ─── */
  document.querySelectorAll('.input-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      var input = this.previousElementSibling;
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        this.textContent = '🙈';
      } else {
        input.type = 'password';
        this.textContent = '👁';
      }
    });
  });

});
