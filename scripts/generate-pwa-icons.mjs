import { readFileSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { Resvg } from '@resvg/resvg-js';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const iconsDir = join(root, 'public', 'icons');
const svg = readFileSync(join(iconsDir, 'memoriq.svg'));

const maskableSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" role="img" aria-label="Memoriq">
  <rect width="512" height="512" fill="#148B74"/>
  <g transform="translate(256 256) scale(7.2) translate(-23.045 -23.045)">
    <rect width="46.09" height="46.09" rx="11.78" fill="#148B74"/>
    <path fill="#fff" d="M31.36 12.5c.66 0 1.2.21 1.62.63.41.42.62.97.62 1.64v16.74c0 .65-.19 1.16-.57 1.53-.38.37-.89.56-1.51.56-.6 0-1.1-.19-1.48-.56-.38-.37-.57-.88-.57-1.53v-10.4l-4.29 7.87c-.28.51-.59.88-.92 1.12-.33.24-.73.35-1.19.35-.44 0-.84-.12-1.18-.35-.34-.24-.65-.61-.94-1.12l-4.29-7.72v10.25c0 .63-.19 1.13-.57 1.52-.38.38-.89.57-1.51.57-.6 0-1.1-.19-1.48-.56-.38-.37-.57-.88-.57-1.53V14.77c0-.67.21-1.21.62-1.64.41-.42.95-.63 1.62-.63.97 0 1.7.5 2.21 1.5l6.14 11.34 6.11-11.34c.54-1 1.27-1.5 2.18-1.5z"/>
  </g>
</svg>`;

function renderPng(source, size, outputName) {
  const resvg = new Resvg(source, {
    fitTo: { mode: 'width', value: size },
  });
  writeFileSync(join(iconsDir, outputName), resvg.render().asPng());
  console.log(`Wrote ${outputName}`);
}

for (const size of [180, 192, 512]) {
  renderPng(svg, size, `memoriq-${size}.png`);
}

renderPng(Buffer.from(maskableSvg), 512, 'memoriq-maskable-512.png');
