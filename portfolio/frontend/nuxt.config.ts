import Aura from '@primeuix/themes/aura';

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: [
    'primeicons/primeicons.css',
    'primeflex/primeflex.css',
    '~/assets/css/main.css'
  ],
  modules: [
      '@primevue/nuxt-module'
  ],
  primevue: {
    options: {
      ripple: true,
      theme: {
          preset: Aura,
          darkModeSelector: false,
      }
    },
  },

  build: {
    transpile: [
      'primevue'
    ]
  }  
})
