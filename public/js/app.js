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
  var sidebarOverlay = document.getElementById('sidebar-overlay');

  function openSidebar() {
    if (sidebar) sidebar.classList.add('open');
    if (sidebarOverlay) sidebarOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('open');
    if (sidebarOverlay) sidebarOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      if (sidebar.classList.contains('open')) { closeSidebar(); } else { openSidebar(); }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
      if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
        closeSidebar();
      }
    });
  }

  // Overlay click menutup sidebar
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
  }

  /* ─── Top Nav Hamburger (halaman publik) ─── */
  var topHamburger = document.getElementById('topnav-hamburger');
  var mobileDrawer = document.getElementById('mobile-nav-drawer');

  if (topHamburger && mobileDrawer) {
    topHamburger.addEventListener('click', function () {
      var isOpen = mobileDrawer.classList.contains('open');
      mobileDrawer.classList.toggle('open');
      topHamburger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      topHamburger.innerHTML = isOpen
        ? '<svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>'
        : '<svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
    });
    document.addEventListener('click', function (e) {
      if (mobileDrawer.classList.contains('open') && !mobileDrawer.contains(e.target) && !topHamburger.contains(e.target)) {
        mobileDrawer.classList.remove('open');
        topHamburger.setAttribute('aria-expanded', 'false');
        topHamburger.innerHTML = '<svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
      }
    });
  }

  // Escape key menutup semua
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeSidebar();
      if (mobileDrawer) mobileDrawer.classList.remove('open');
    }
  });

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
