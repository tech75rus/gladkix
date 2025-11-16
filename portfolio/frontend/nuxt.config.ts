// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: [
    'primeicons/primeicons.css',
    'primeflex/primeflex.css',
    '~/assets/css/main.css'
  ],
  
  build: {
    transpile: ['primevue']
  },
  
  plugins: [
    '~/plugins/primevue.js'
  ]
})
