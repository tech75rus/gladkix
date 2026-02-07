import { definePreset } from '@primevue/themes';
import Aura from '@primeuix/themes/aura';

// Создаем кастомную тему
const MyPreset = definePreset(Aura, {
  semantic: {
    primary: {
      50: '#dce8f2',
      100: '#c5d8ea',
      200: '#9bbade', 
      300: '#729cd2',
      400: '#4e74bf',
      500: '#3a5ba6',
      600: '#2b4580',
      700: '#1f3260',
      800: '#162547',
      900: '#101C40',
      950: '#0a1226'
    },
  }
});

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  devServer: {
    host: '0.0.0.0',
    port: 3000
  },
  nitro: {
    devProxy: {
      '/api': {
        target: 'http://localhost:80/api', // Бэкенд на компьютере
      }
    }
  },
  css: [
    'primeicons/primeicons.css',
    '~/assets/css/main.css',
    '~/assets/css/custom-color.css',
  ],
  modules: [
      '@primevue/nuxt-module',
      '@nuxt/icon',
      '@nuxtjs/tailwindcss',
  ],
  primevue: {
    options: {
      ripple: true,
      theme: {
        preset: MyPreset,
        options: {
            darkModeSelector: false,
        }
      }
    },
  },

  build: {
    transpile: [
      'primevue'
    ]
  },
})
