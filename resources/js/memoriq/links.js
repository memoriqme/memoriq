export const GITHUB_URL = 'https://github.com/memoriqme';

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
