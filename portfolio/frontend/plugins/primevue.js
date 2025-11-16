import PrimeVue from 'primevue/config'
import Aura from '@primevue/themes/aura'

// Компоненты
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Card from 'primevue/card'
import Toast from 'primevue/toast'
import ToastService from 'primevue/toastservice'
import Dialog from 'primevue/dialog'
import Dropdown from 'primevue/dropdown'
import Tag from 'primevue/tag'
import Paginator from 'primevue/paginator'
import Menu from 'primevue/menu'
import Avatar from 'primevue/avatar'

export default defineNuxtPlugin((nuxtApp) => {
  // ✅ Настраиваем PrimeVue с темой
  nuxtApp.vueApp.use(PrimeVue, {
    theme: {
      preset: Aura,
      options: {
        darkModeSelector: false
      }
    }
  })
  
  // ✅ Инициализируем ToastService
  nuxtApp.vueApp.use(ToastService)

  // ✅ Регистрируем компоненты
  const components = {
    Button,
    InputText, 
    DataTable,
    Column,
    Card,
    Toast,
    Dialog,
    Dropdown,
    Tag,
    Paginator,
    Menu,
    Avatar
  }

  Object.entries(components).forEach(([name, component]) => {
    nuxtApp.vueApp.component(name, component)
  })
})