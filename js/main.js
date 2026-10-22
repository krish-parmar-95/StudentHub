/* ============================================================
   StudentHub – Practical 4: JavaScript DOM Manipulation,
   Event Handling & UI Interactivity
   Skills: DOM selection, event handling, class manipulation,
           localStorage, basic animation
   ============================================================ */

/* ── 1. THEME SWITCHER (localStorage) ─────────────────────── */
(function initTheme() {
  const saved = localStorage.getItem('sh-theme');
  if (saved === 'dark') document.documentElement.classList.add('dark-mode');
})();

function toggleTheme() {
  const root = document.documentElement;
  root.classList.toggle('dark-mode');
  const isDark = root.classList.contains('dark-mode');
  localStorage.setItem('sh-theme', isDark ? 'dark' : 'light');
  const btn = document.getElementById('theme-btn');
  if (btn) btn.textContent = isDark ? '☀️ Light Mode' : '🌙 Dark Mode';
}

/* ── 2. HAMBURGER MENU ─────────────────────────────────────── */
function toggleMenu() {
  const nav = document.getElementById('main-nav');
  if (!nav) return;
  nav.classList.toggle('nav-open');
  const btn = document.getElementById('hamburger-btn');
  if (btn) btn.setAttribute('aria-expanded', nav.classList.contains('nav-open'));
}

/* ── 3. NOTIFICATION BANNER ────────────────────────────────── */
function showNotification(message, type = 'info') {
  let banner = document.getElementById('notification-banner');
  if (!banner) {
    banner = document.createElement('div');
    banner.id = 'notification-banner';
    document.body.prepend(banner);
  }
  banner.textContent = message;
  banner.className = 'notification ' + type;
  banner.style.display = 'block';
  clearTimeout(banner._timer);
  banner._timer = setTimeout(() => {
    banner.style.opacity = '0';
    setTimeout(() => { banner.style.display = 'none'; banner.style.opacity = '1'; }, 500);
  }, 3500);
}

/* ── 4. COLLAPSIBLE FAQ (JS-powered accordion) ─────────────── */
function initFAQ() {
  const items = document.querySelectorAll('.faq-item');
  items.forEach(item => {
    const question = item.querySelector('.faq-question');
    const answer   = item.querySelector('.faq-answer');
    if (!question || !answer) return;

    question.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');
      // close all
      items.forEach(i => {
        i.classList.remove('open');
        const a = i.querySelector('.faq-answer');
        if (a) a.style.maxHeight = null;
      });
      // open clicked 
      if (!isOpen) {
        item.classList.add('open');
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });
}

/* ── 5. MODAL POPUP ────────────────────────────────────────── */
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.add('modal-active');
  modal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
  // trap focus on close button
  const closeBtn = modal.querySelector('.modal-close');
  if (closeBtn) closeBtn.focus();
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.remove('modal-active');
  modal.setAttribute('aria-hidden', 'true');
  document.body.style.overflow = '';
}

// close on backdrop click or Escape key
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) {
    closeModal(e.target.id);
  }
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.modal-active').forEach(m => closeModal(m.id));
  }
});


/* ── 6. IMAGE / CONTENT SLIDER ─────────────────────────────── */
function initSlider(sliderId) {
  const slider = document.getElementById(sliderId);
  if (!slider) return;

  const slides = slider.querySelectorAll('.slide');
  let current = 0;

  function showSlide(index) {
    slides.forEach((s, i) => s.classList.toggle('slide-active', i === index));
    const dots = slider.querySelectorAll('.slider-dot');
    dots.forEach((d, i) => d.classList.toggle('dot-active', i === index));
  }

  // prev / next buttons
  const prevBtn = slider.querySelector('.slider-prev');
  const nextBtn = slider.querySelector('.slider-next');
  if (prevBtn) prevBtn.addEventListener('click', () => { current = (current - 1 + slides.length) % slides.length; showSlide(current); });
  if (nextBtn) nextBtn.addEventListener('click', () => { current = (current + 1) % slides.length; showSlide(current); });

  // dot navigation
  const dotsWrap = slider.querySelector('.slider-dots');
  if (dotsWrap) {
    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'slider-dot';
      dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
      dot.addEventListener('click', () => { current = i; showSlide(current); });
      dotsWrap.appendChild(dot);
    });
  }

  // auto-play every 4 s
  setInterval(() => { current = (current + 1) % slides.length; showSlide(current); }, 4000);

  showSlide(0);
}

/* ── 7. INIT ON DOM READY ──────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  initFAQ();
  initSlider('feature-slider');

  // set theme button label on load
  const themeBtn = document.getElementById('theme-btn');
  if (themeBtn) {
    const isDark = document.documentElement.classList.contains('dark-mode');
    themeBtn.textContent = isDark ? '☀️ Light Mode' : '🌙 Dark Mode';
  }

  // welcome notification
  showNotification('👋 Welcome to StudentHub! Practical 4 – JS Interactivity is live.', 'info');
});
