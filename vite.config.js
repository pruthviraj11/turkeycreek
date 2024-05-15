// vite.config.js

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  build: {
    outDir: 'public/build', // Output directory
    manifest: true, // Enable manifest generation
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      output: 'public/build',
    }),
  ],
});
