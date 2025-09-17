// Vite config con manifest (hash para prod)
import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  root: '.',
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        app: path.resolve(__dirname, 'resources/js/app.js'),
        styles: path.resolve(__dirname, 'resources/css/app.css'),
      }
    }
  }
});