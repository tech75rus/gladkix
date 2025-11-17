<template>
  <div>
    <!-- Заголовок и действия -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-surface-900">Проекты</h1>
        <p class="text-surface-600">Управление проектами портфолио</p>
      </div>
      <Button 
        label="Новый проект" 
        icon="pi pi-plus" 
        @click="showCreateDialog = true"
      />
    </div>

    <!-- Фильтры -->
    <Card class="mb-4">
      <template #content>
        <div class="flex flex-wrap gap-4">
          <div class="field">
            <label class="font-medium text-sm">Статус</label>
            <Dropdown 
              v-model="filters.status" 
              :options="statusOptions" 
              placeholder="Все статусы"
              class="w-48"
            />
          </div>
          <div class="field">
            <label class="font-medium text-sm">Поиск</label>
            <InputText 
              v-model="filters.search" 
              placeholder="Поиск по названию..." 
              class="w-64"
            />
          </div>
        </div>
      </template>
    </Card>

    <!-- Таблица проектов -->
    <Card>
      <template #content>
        <DataTable :value="filteredProjects" paginator :rows="10">
          <Column field="id" header="ID" style="width: 80px"></Column>
          <Column field="title" header="Название проекта">
            <template #body="slotProps">
              <div class="flex items-center gap-3">
                <img 
                  :src="slotProps.data.cover_image || '/placeholder-project.jpg'" 
                  :alt="slotProps.data.title"
                  class="w-12 h-12 object-cover rounded-lg"
                >
                <div>
                  <div class="font-medium">{{ slotProps.data.title }}</div>
                  <div class="text-xs text-surface-500">{{ slotProps.data.slug }}</div>
                </div>
              </div>
            </template>
          </Column>
          <Column field="status" header="Статус" style="width: 140px">
            <template #body="slotProps">
              <Tag 
                :value="getStatusLabel(slotProps.data.status)" 
                :severity="getStatusSeverity(slotProps.data.status)"
              />
            </template>
          </Column>
          <Column header="Ссылки" style="width: 180px">
            <template #body="slotProps">
              <div class="flex gap-2">
                <Button 
                  v-if="slotProps.data.github_url"
                  icon="pi pi-github" 
                  text rounded 
                  severity="secondary"
                  v-tooltip="'GitHub'"
                  @click="openUrl(slotProps.data.github_url)"
                />
                <Button 
                  v-if="slotProps.data.demo_url"
                  icon="pi pi-external-link" 
                  text rounded 
                  severity="info"
                  v-tooltip="'Демо'"
                  @click="openUrl(slotProps.data.demo_url)"
                />
                <Button 
                  v-if="slotProps.data.project_url"
                  icon="pi pi-globe" 
                  text rounded 
                  severity="help"
                  v-tooltip="'Проект'"
                  @click="openUrl(slotProps.data.project_url)"
                />
              </div>
            </template>
          </Column>
          <Column field="tags" header="Теги" style="width: 200px">
            <template #body="slotProps">
              <div class="flex flex-wrap gap-1">
                <Tag 
                  v-for="tag in slotProps.data.tags.slice(0, 2)" 
                  :key="tag.id"
                  :value="tag.name" 
                  severity="info"
                  class="text-xs"
                />
                <Tag 
                  v-if="slotProps.data.tags.length > 2"
                  :value="`+${slotProps.data.tags.length - 2}`" 
                  severity="secondary"
                  class="text-xs"
                />
              </div>
            </template>
          </Column>
          <Column header="Действия" style="width: 120px">
            <template #body="slotProps">
              <div class="flex gap-2">
                <Button 
                  icon="pi pi-eye" 
                  text rounded 
                  severity="info"
                  v-tooltip="'Просмотр'"
                />
                <Button 
                  icon="pi pi-pencil" 
                  text rounded 
                  severity="warning"
                  @click="editProject(slotProps.data)"
                  v-tooltip="'Редактировать'"
                />
                <Button 
                  icon="pi pi-trash" 
                  text rounded 
                  severity="danger"
                  @click="deleteProject(slotProps.data)"
                  v-tooltip="'Удалить'"
                />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <!-- Диалог создания/редактирования проекта -->
    <Dialog 
      v-model:visible="showCreateDialog" 
      modal 
      :header="editingProject ? 'Редактировать проект' : 'Новый проект'"
      :style="{ width: '90vw', maxWidth: '1200px' }"
      :breakpoints="{ '960px': '95vw', '641px': '100vw' }"
    >
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Основная информация -->
        <div class="lg:col-span-2 flex flex-col gap-4">
          <div class="field">
            <label class="font-medium">Название проекта *</label>
            <InputText 
              v-model="projectForm.title" 
              class="w-full" 
              placeholder="Введите название проекта..."
            />
          </div>

          <div class="field">
            <label class="font-medium">URL slug *</label>
            <InputText 
              v-model="projectForm.slug" 
              class="w-full" 
              placeholder="project-url-slug"
            />
          </div>

          <div class="field">
            <label class="font-medium">Краткое описание</label>
            <Textarea 
              v-model="projectForm.description" 
              class="w-full" 
              rows="3"
              placeholder="Краткое описание проекта..."
            />
          </div>

          <div class="field">
            <label class="font-medium">Подробное описание</label>
            <Editor 
              v-model="projectForm.content" 
              editorStyle="height: 320px"
              :modules="editorModules"
            />
          </div>
        </div>

        <!-- Боковая панель -->
        <div class="flex flex-col gap-4">
          <!-- Статус и ссылки -->
          <Card>
            <template #title>Статус и ссылки</template>
            <template #content>
              <div class="flex flex-col gap-3">
                <div class="field">
                  <label class="font-medium text-sm">Статус проекта *</label>
                  <Dropdown 
                    v-model="projectForm.status" 
                    :options="statusOptions" 
                    optionLabel="label"
                    placeholder="Выберите статус"
                    class="w-full"
                  />
                </div>
                <div class="field">
                  <label class="font-medium text-sm">GitHub URL</label>
                  <InputText 
                    v-model="projectForm.github_url" 
                    class="w-full" 
                    placeholder="https://github.com/user/repo"
                  />
                </div>
                <div class="field">
                  <label class="font-medium text-sm">URL проекта</label>
                  <InputText 
                    v-model="projectForm.project_url" 
                    class="w-full" 
                    placeholder="https://myproject.com"
                  />
                </div>
                <div class="field">
                  <label class="font-medium text-sm">Демо URL</label>
                  <InputText 
                    v-model="projectForm.demo_url" 
                    class="w-full" 
                    placeholder="https://demo.myproject.com"
                  />
                </div>
              </div>
            </template>
          </Card>

          <!-- Теги и изображение -->
          <Card>
            <template #title>Теги и изображение</template>
            <template #content>
              <div class="flex flex-col gap-3">
                <div class="field">
                  <label class="font-medium text-sm">Теги проекта</label>
                  <MultiSelect 
                    v-model="projectForm.tag_ids" 
                    :options="tags" 
                    optionLabel="name"
                    placeholder="Выберите теги"
                    class="w-full"
                    :maxSelectedLabels="3"
                  />
                </div>
                <div class="field">
                  <label class="font-medium text-sm">Обложка проекта</label>
                  <FileUpload 
                    mode="basic"
                    chooseLabel="Выбрать изображение"
                    class="w-full"
                    @select="onImageSelect"
                  />
                  <div v-if="projectForm.cover_image" class="mt-2">
                    <img 
                      :src="projectForm.cover_image" 
                      alt="Обложка"
                      class="w-20 h-20 object-cover rounded"
                    >
                  </div>
                </div>
              </div>
            </template>
          </Card>

          <!-- Даты -->
          <Card>
            <template #title>Даты</template>
            <template #content>
              <div class="flex flex-col gap-3">
                <div class="field">
                  <label class="font-medium text-sm">Дата создания</label>
                  <Calendar 
                    v-model="projectForm.created_at" 
                    class="w-full"
                    dateFormat="dd.mm.yy"
                  />
                </div>
                <div class="field">
                  <label class="font-medium text-sm">Дата обновления</label>
                  <Calendar 
                    v-model="projectForm.updated_at" 
                    class="w-full"
                    dateFormat="dd.mm.yy"
                  />
                </div>
              </div>
            </template>
          </Card>
        </div>
      </div>

      <template #footer>
        <Button 
          label="Отмена" 
          severity="secondary" 
          @click="closeDialog" 
        />
        <Button 
          :label="editingProject ? 'Обновить проект' : 'Создать проект'" 
          @click="saveProject" 
          :disabled="!projectForm.title || !projectForm.status"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})

// Состояние
const showCreateDialog = ref(false)
const editingProject = ref(null)

// Фильтры
const filters = reactive({
  status: null,
  search: ''
})

// Форма проекта
const projectForm = reactive({
  title: '',
  slug: '',
  description: '',
  content: '',
  status: null,
  github_url: '',
  project_url: '',
  demo_url: '',
  cover_image: '',
  tag_ids: [],
  created_at: null,
  updated_at: null
})

// Опции статусов
const statusOptions = ref([
  { value: 'planning', label: 'Планирование' },
  { value: 'in_progress', label: 'В разработке' },
  { value: 'completed', label: 'Завершен' },
  { value: 'on_hold', label: 'На паузе' }
])

// Mock данные проектов
const projects = ref([
  {
    id: 1,
    title: 'Портфолио сайт на Nuxt 3',
    slug: 'nuxt3-portfolio',
    description: 'Современное портфолио с админкой на Nuxt 3 и PrimeVue',
    content: '<p>Полное описание проекта...</p>',
    status: 'completed',
    github_url: 'https://github.com/user/portfolio',
    project_url: 'https://myportfolio.com',
    demo_url: 'https://demo.myportfolio.com',
    cover_image: '/portfolio-cover.jpg',
    tags: [
      { id: 2, name: 'Nuxt' },
      { id: 1, name: 'Vue.js' },
      { id: 4, name: 'TypeScript' }
    ],
    created_at: '2024-01-01',
    updated_at: '2024-01-15'
  },
  {
    id: 2,
    title: 'E-commerce платформа',
    slug: 'ecommerce-platform',
    description: 'Полнофункциональная платформа для интернет-магазина',
    content: '<p>Описание e-commerce проекта...</p>',
    status: 'in_progress',
    github_url: 'https://github.com/user/ecommerce',
    project_url: '',
    demo_url: '',
    cover_image: '/ecommerce-cover.jpg',
    tags: [
      { id: 3, name: 'JavaScript' },
      { id: 5, name: 'Symfony' }
    ],
    created_at: '2024-01-10',
    updated_at: '2024-01-20'
  },
  {
    id: 3,
    title: 'Мобильное приложение для трекинга',
    slug: 'mobile-tracking-app',
    description: 'Приложение для отслеживания привычек и целей',
    content: '<p>Описание мобильного приложения...</p>',
    status: 'planning',
    github_url: '',
    project_url: '',
    demo_url: '',
    cover_image: '',
    tags: [
      { id: 4, name: 'TypeScript' },
      { id: 6, name: 'React Native' }
    ],
    created_at: '2024-01-18',
    updated_at: '2024-01-18'
  }
])

// Теги (можно вынести в стор)
const tags = ref([
  { id: 1, name: 'Vue.js' },
  { id: 2, name: 'Nuxt' },
  { id: 3, name: 'JavaScript' },
  { id: 4, name: 'TypeScript' },
  { id: 5, name: 'Symfony' },
  { id: 6, name: 'React Native' },
  { id: 7, name: 'Node.js' },
  { id: 8, name: 'PostgreSQL' }
])

// Вычисляемые свойства
const filteredProjects = computed(() => {
  let filtered = projects.value

  if (filters.status) {
    filtered = filtered.filter(project => project.status === filters.status.value)
  }

  if (filters.search) {
    const searchLower = filters.search.toLowerCase()
    filtered = filtered.filter(project => 
      project.title.toLowerCase().includes(searchLower) ||
      project.description.toLowerCase().includes(searchLower)
    )
  }

  return filtered
})

// Методы
const getStatusLabel = (status) => {
  const statusOption = statusOptions.value.find(opt => opt.value === status)
  return statusOption ? statusOption.label : status
}

const getStatusSeverity = (status) => {
  const severities = {
    planning: 'secondary',
    in_progress: 'warning',
    completed: 'success',
    on_hold: 'danger'
  }
  return severities[status] || 'info'
}

const openUrl = (url) => {
  window.open(url, '_blank')
}

const onImageSelect = (event) => {
  // В реальном приложении здесь была бы загрузка на сервер
  const file = event.files[0]
  if (file) {
    projectForm.cover_image = URL.createObjectURL(file)
  }
}

const editProject = (project) => {
  editingProject.value = project
  Object.assign(projectForm, {
    ...project,
    tag_ids: project.tags.map(tag => tag.id),
    status: statusOptions.value.find(opt => opt.value === project.status)
  })
  showCreateDialog.value = true
}

const deleteProject = (project) => {
  if (confirm(`Удалить проект "${project.title}"?`)) {
    projects.value = projects.value.filter(p => p.id !== project.id)
  }
}

const saveProject = () => {
  if (editingProject.value) {
    // Обновляем проект
    const index = projects.value.findIndex(p => p.id === editingProject.value.id)
    const selectedTags = tags.value.filter(tag => projectForm.tag_ids.includes(tag.id))
    
    projects.value[index] = {
      ...projectForm,
      id: editingProject.value.id,
      tags: selectedTags,
      status: projectForm.status.value,
      updated_at: new Date().toISOString().split('T')[0]
    }
  } else {
    // Создаем новый проект
    const selectedTags = tags.value.filter(tag => projectForm.tag_ids.includes(tag.id))
    const now = new Date().toISOString().split('T')[0]
    
    const newProject = {
      ...projectForm,
      id: Date.now(),
      status: projectForm.status.value,
      tags: selectedTags,
      created_at: now,
      updated_at: now
    }
    projects.value.unshift(newProject)
  }
  
  closeDialog()
}

const closeDialog = () => {
  showCreateDialog.value = false
  editingProject.value = null
  Object.assign(projectForm, {
    title: '',
    slug: '',
    description: '',
    content: '',
    status: null,
    github_url: '',
    project_url: '',
    demo_url: '',
    cover_image: '',
    tag_ids: [],
    created_at: null,
    updated_at: null
  })
}

// Настройки редактора
const editorModules = ref([])
</script>

<style scoped>
.field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.field-checkbox {
  display: flex;
  align-items: center;
}
</style>