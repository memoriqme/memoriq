const root = document.documentElement;
const landing = document.querySelector('.mvp-landing');
const themeToggle = document.querySelector('#theme-toggle');
const navToggle = document.querySelector('#nav-toggle');
const navLinks = document.querySelector('#nav-links');
const demoMeta = {
  'chatgpt-saving': {
    title: 'Which AI chats are worth saving?',
    provider: 'ChatGPT',
    providerClass: 'source-chatgpt',
    date: 'Jun 5, 2026',
    size: '128 KB',
  },
  'gemini-organize': {
    title: 'How should I organize saved AI chats?',
    provider: 'Gemini',
    providerClass: 'source-gemini',
    date: 'Jun 6, 2026',
    size: '96 KB',
  },
  'claude-vault': {
    title: 'How does private AI memory work?',
    provider: 'Claude',
    providerClass: 'source-claude',
    date: 'Jun 4, 2026',
    size: '112 KB',
  },
};

function setTheme(theme) {
  root.dataset.theme = theme;
  landing.dataset.landingTheme = theme;
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

navToggle?.addEventListener('click', () => {
  const open = navLinks?.classList.toggle('open') ?? false;
  navToggle.setAttribute('aria-expanded', String(open));
  document.querySelector('.nav-icon-menu')?.toggleAttribute('hidden', open);
  document.querySelector('.nav-icon-close')?.toggleAttribute('hidden', !open);
});

document.querySelectorAll('a[href^="#"]').forEach((link) => {
  link.addEventListener('click', (event) => {
    const target = document.querySelector(link.getAttribute('href'));
    if (!target) return;

    event.preventDefault();
    navLinks?.classList.remove('open');
    navToggle?.setAttribute('aria-expanded', 'false');
    document.querySelector('.nav-icon-menu')?.removeAttribute('hidden');
    document.querySelector('.nav-icon-close')?.setAttribute('hidden', '');
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    history.replaceState(null, '', link.getAttribute('href'));
  });
});

document.querySelectorAll('.preview-thread').forEach((thread) => {
  thread.addEventListener('click', () => {
    const chatId = thread.dataset.chat;
    const meta = demoMeta[chatId];
    if (!meta) return;

    document.querySelectorAll('.preview-thread').forEach((item) => {
      item.classList.toggle('active', item === thread);
    });
    document.querySelectorAll('.demo-chat-panel').forEach((panel) => {
      panel.toggleAttribute('hidden', panel.dataset.chatPanel !== chatId);
    });

    document.querySelector('.preview-title').textContent = meta.title;
    const source = document.querySelector('[data-demo-source]');
    source.textContent = meta.provider;
    source.className = `mini-badge ${meta.providerClass}`;
    document.querySelector('[data-demo-date]').textContent = meta.date;
    document.querySelector('[data-demo-size]').textContent = meta.size;
    document.querySelector('.preview-main')?.scrollTo({ top: 0, behavior: 'smooth' });
  });
});

const newsletter = document.querySelector('#newsletter');
const newsletterForm = document.querySelector('#newsletter-form');
const newsletterFeedback = document.querySelector('#newsletter-feedback');

newsletterForm?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const input = newsletterForm.querySelector('input[type="email"]');
  const button = newsletterForm.querySelector('button');
  const email = input?.value.trim();
  const endpoint = newsletter?.dataset.newsletterUrl;
  if (!email || !endpoint) return;

  button.disabled = true;
  button.textContent = 'Subscribing...';
  newsletterFeedback.textContent = '';
  newsletterFeedback.className = '';

  try {
    const separator = endpoint.includes('?') ? '&' : '?';
    const response = await fetch(
      `${endpoint}${separator}action=mailing_list&data=${encodeURIComponent(email)}`,
      { method: 'POST' },
    );
    const result = (await response.text()).trim();

    if (response.ok && result === 'success') {
      newsletterFeedback.textContent = 'Subscribed!';
      newsletterFeedback.className = 'newsletter-feedback newsletter-feedback-success';
      newsletterForm.reset();
    } else if (result === 'email error') {
      throw new Error('Invalid email address.');
    } else if (result === 'limit error') {
      throw new Error('Please wait before trying again.');
    } else {
      throw new Error('Something went wrong. Please try again.');
    }
  } catch (error) {
    newsletterFeedback.textContent = error.message;
    newsletterFeedback.className = 'newsletter-feedback newsletter-feedback-error';
  } finally {
    button.disabled = false;
    button.textContent = 'Subscribe';
  }
});
