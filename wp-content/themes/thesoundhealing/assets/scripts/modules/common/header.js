(function () {
  const nav     = document.getElementById('hd-nav');
  const btnOpen = document.querySelector('.js-nav-open');
  const btnClose = document.querySelector('.js-nav-close');

  if (!nav) return;

  // Tạo overlay
  const overlay = document.createElement('div');
  overlay.className = 'hd-overlay';
  document.body.appendChild(overlay);

  function openNav() {
    nav.classList.add('is-open');
    overlay.classList.add('is-open');
    nav.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeNav() {
    nav.classList.remove('is-open');
    overlay.classList.remove('is-open');
    nav.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  btnOpen?.addEventListener('click', openNav);
  btnClose?.addEventListener('click', closeNav);
  overlay.addEventListener('click', closeNav);

  // Đóng khi bấm Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeNav();
  });
})();
