import PrimeVue from 'primevue/config'
import Aura from '@primevue/themes/aura'

// Базовые компоненты
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
import Textarea from 'primevue/textarea'
import ColorPicker from 'primevue/colorpicker'
import Checkbox from 'primevue/checkbox'
import Editor from 'primevue/editor'
import MultiSelect from 'primevue/multiselect'
import Calendar from 'primevue/calendar'
import FileUpload from 'primevue/fileupload'
import InputNumber from 'primevue/inputnumber'

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.use(PrimeVue, {
    theme: {
      preset: Aura,
      options: {
        darkModeSelector: false
      }
    }
  })
  
  nuxtApp.vueApp.use(ToastService)

  const components = {
    Button, InputText, DataTable, Column, Card, Toast,
    Dialog, Dropdown, Tag, Paginator, Menu, Avatar,
    Textarea, ColorPicker, Checkbox,
    Editor, MultiSelect, Calendar, FileUpload, InputNumber
  }

  Object.entries(components).forEach(([name, component]) => {
    nuxtApp.vueApp.component(name, component)
  })
})