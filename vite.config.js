// react/vite.config.js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

// Configuración de Vite
export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './front/src'), // Alias para los archivos JS dentro de la carpeta "src" de React
    },
  },
  server: {
    proxy: {
      '/api': 'http://localhost:8000',  // Redirige las solicitudes API hacia el servidor de Laravel (por defecto localhost)
    },
  },
});
