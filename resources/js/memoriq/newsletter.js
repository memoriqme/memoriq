function envFlag(value) {
  return value === true || value === 'true' || value === '1';
}

const configuredUrl = (import.meta.env.VITE_NEWSLETTER_URL || '').trim();

export const newsletterEnabled =
  envFlag(import.meta.env.VITE_NEWSLETTER_ENABLED) && configuredUrl.length > 0;

/** Landing-page external subscribe form only. Registration uses the app DB separately. */
export const newsletterLandingEnabled = newsletterEnabled;

export const newsletterUrl = configuredUrl;

export function newsletterSubscribeUrl(email) {
  const separator = newsletterUrl.includes('?') ? '&' : '?';

  return `${newsletterUrl}${separator}action=mailing_list&data=${encodeURIComponent(email)}`;
}
