const root = document.documentElement;
const themeToggle = document.querySelector('#theme-toggle');

function setTheme(theme) {
  root.dataset.theme = theme;
  localStorage.setItem('memoriq-theme', theme);
  themeToggle?.setAttribute(
    'aria-label',
    theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode',
  );
  document.querySelector('.theme-icon-sun')?.toggleAttribute('hidden', theme !== 'dark');
  document.querySelector('.theme-icon-moon')?.toggleAttribute('hidden', theme === 'dark');
}

setTheme(root.dataset.theme === 'light' ? 'light' : 'dark');

themeToggle?.addEventListener('click', () => {
  setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
});
