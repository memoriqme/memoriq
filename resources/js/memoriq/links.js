export const GITHUB_URL = 'https://github.com/memoriqme';

export const X_URL = 'https://x.com/memoriqme';

export const INSTAGRAM_URL = 'https://instagram.com/memoriqme';

export const BLUESKY_URL = 'https://bsky.app/profile/memoriq.bsky.social';

export const GITHUB_EXTENSION_URL = `${GITHUB_URL}/memoriq-extension`;

export const CHROME_EXTENSION_URL =
  'https://chromewebstore.google.com/detail/memoriq/jhhjcchhlfodciphfacegemnemmjdmci';

export const EXTENSION_CONNECT_URL = '/extension/connect';

export function extensionInstallUrl() {
  return CHROME_EXTENSION_URL;
}

export function extensionInstallLabel() {
  return 'Install Chrome extension';
}
