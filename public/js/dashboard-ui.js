(function () {
  const doc = document.documentElement;
  const body = document.body;
  const THEME_KEY = 'dc_theme';
  function applyTheme(theme) {
    if (theme === 'dark') {
      body.classList.add('theme-dark');
    } else {
      body.classList.remove('theme-dark');
    }
  }
  const saved = localStorage.getItem(THEME_KEY);
  applyTheme(saved || 'light');

  const themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const isDark = body.classList.contains('theme-dark');
      const next = isDark ? 'light' : 'dark';
      localStorage.setItem(THEME_KEY, next);
      applyTheme(next);
    });
  }

  const drawer = document.getElementById('notifDrawer');
  const openBtn = document.getElementById('notifToggle');
  const closeBtn = document.getElementById('notifClose');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');
  }
  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
  }

  if (openBtn && drawer) openBtn.addEventListener('click', () => {
    const open = drawer.classList.contains('is-open');
    open ? closeDrawer() : openDrawer();
  });
  if (closeBtn && drawer) closeBtn.addEventListener('click', closeDrawer);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDrawer();
  });

  document.addEventListener('click', (e) => {
    if (!drawer || !openBtn) return;
    const t = e.target;
    if (!drawer.classList.contains('is-open')) return;
    if (drawer.contains(t) || openBtn.contains(t)) return;
    closeDrawer();
  });
})();
