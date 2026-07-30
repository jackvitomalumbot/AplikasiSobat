/* ═══════════════════════════════════════════
   SobatMedis — app.js
   Carousel, Sidebar Toggle, Modal, Scroll Animations, Mobile Nav
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

    function updateButtons() {
      const maxOffset = track.scrollWidth - track.parentElement.offsetWidth;
      prevBtn.style.opacity = offset <= 0 ? '0.4' : '1';
      nextBtn.style.opacity = offset >= maxOffset ? '0.4' : '1';
    }

    nextBtn.addEventListener('click', function () {
      const maxOffset = track.scrollWidth - track.parentElement.offsetWidth;
      offset = Math.min(offset + cardWidth, maxOffset);
      track.style.transform = 'translateX(-' + offset + 'px)';
      updateButtons();
    });

    prevBtn.addEventListener('click', function () {
      offset = Math.max(offset - cardWidth, 0);
      track.style.transform = 'translateX(-' + offset + 'px)';
      updateButtons();
    });

    // Touch / swipe support for carousel
    let touchStartX = 0;
    let touchEndX = 0;
    track.addEventListener('touchstart', function (e) {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    track.addEventListener('touchend', function (e) {
      touchEndX = e.changedTouches[0].screenX;
      const diff = touchStartX - touchEndX;
      if (Math.abs(diff) > 50) {
        if (diff > 0) {
          nextBtn.click();
        } else {
          prevBtn.click();
        }
      }
    }, { passive: true });

    updateButtons();
  });

  /* ─── Sidebar Toggle (Mobile — Dashboard/Admin pages) ─── */
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
      if (sidebar.classList.contains('open')) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });
  }

  // Close sidebar when overlay is clicked
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
  }

  // Close sidebar when a sidebar link is clicked on mobile
  if (sidebar) {
    sidebar.querySelectorAll('.sidebar-link').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth <= 768) {
          closeSidebar();
        }
      });
    });
  }

  /* ─── Top Nav Hamburger (Public pages) ─── */
  var topnavHamburger = document.getElementById('topnav-hamburger');
  var mobileNavDrawer = document.getElementById('mobile-nav-drawer');

  if (topnavHamburger && mobileNavDrawer) {
    topnavHamburger.addEventListener('click', function () {
      var isOpen = mobileNavDrawer.classList.contains('open');
      if (isOpen) {
        mobileNavDrawer.classList.remove('open');
        topnavHamburger.setAttribute('aria-expanded', 'false');
        // Switch to hamburger icon
        topnavHamburger.innerHTML = '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
      } else {
        mobileNavDrawer.classList.add('open');
        topnavHamburger.setAttribute('aria-expanded', 'true');
        // Switch to close (X) icon
        topnavHamburger.innerHTML = '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
      }
    });

    // Close drawer when clicking outside
    document.addEventListener('click', function (e) {
      if (mobileNavDrawer.classList.contains('open') &&
          !mobileNavDrawer.contains(e.target) &&
          !topnavHamburger.contains(e.target)) {
        mobileNavDrawer.classList.remove('open');
        topnavHamburger.setAttribute('aria-expanded', 'false');
        topnavHamburger.innerHTML = '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
      }
    });
  }

  /* ─── Modal ─── */
  document.querySelectorAll('[data-modal-target]').forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      var target = document.querySelector(this.dataset.modalTarget);
      if (target) {
        target.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  document.querySelectorAll('.modal-close, [data-modal-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var overlay = this.closest('.modal-overlay');
      if (overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  });

  document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  });

  // Close modal on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.active').forEach(function (overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      });
      closeSidebar();
      if (mobileNavDrawer) mobileNavDrawer.classList.remove('open');
    }
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
        this.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
      } else {
        input.type = 'password';
        this.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
      }
    });
  });

  /* ─── Auto-hide flash alerts ─── */
  document.querySelectorAll('.alert').forEach(function (alert) {
    setTimeout(function () {
      alert.style.transition = 'opacity 400ms ease, max-height 400ms ease';
      alert.style.opacity = '0';
      alert.style.maxHeight = '0';
      alert.style.overflow = 'hidden';
      alert.style.paddingTop = '0';
      alert.style.paddingBottom = '0';
      alert.style.marginTop = '0';
      alert.style.marginBottom = '0';
      setTimeout(function () { alert.remove(); }, 420);
    }, 5000);
  });

  /* ─── Content padding adjustment on mobile for main-content ─── */
  function adjustContentPadding() {
    var mainContent = document.querySelector('.main-content .content-wrapper');
    if (!mainContent) return;
    if (window.innerWidth <= 768) {
      mainContent.style.padding = 'var(--space-md)';
    } else {
      mainContent.style.padding = 'var(--space-xl)';
    }
  }
  adjustContentPadding();
  window.addEventListener('resize', adjustContentPadding);

});
