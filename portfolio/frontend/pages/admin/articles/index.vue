<template>
  <div>
    <!-- Заголовок и действия -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-surface-900">Статьи</h1>
        <p class="text-surface-600">Управление статьями блога</p>
      </div>
      <Button 
        label="Новая статья" 
        icon="pi pi-plus" 
        @click="showCreateDialog = true"
      />
    </div>

    <!-- Фильтры -->
    <Card class="mb-4">
      <template #content>
        <div class="flex flex-wrap gap-4">
          <div class="field">
            <label class="font-medium text-sm">Категория</label>
            <Dropdown 
              v-model="filters.category" 
              :options="categories" 
              optionLabel="name"
              placeholder="Все категории"
              class="w-48"
            />
          </div>
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

    <!-- Таблица статей -->
    <Card>
      <template #content>
        <DataTable :value="filteredArticles" paginator :rows="10">
          <Column field="id" header="ID" style="width: 80px"></Column>
          <Column field="title" header="Заголовок">
            <template #body="slotProps">
              <div class="flex items-center gap-3">
                <img 
                  :src="slotProps.data.cover_image || '/placeholder-article.jpg'" 
                  :alt="slotProps.data.title"
                  class="w-12 h-8 object-cover rounded"
                >
                <div>
                  <div class="font-medium">{{ slotProps.data.title }}</div>
                  <div class="text-xs text-surface-500">{{ slotProps.data.slug }}</div>
                </div>
              </div>
            </template>
          </Column>
          <Column field="category.name" header="Категория" style="width: 150px">
            <template #body="slotProps">
              <Tag 
                :value="slotProps.data.category.name" 
                :style="{ 
                  backgroundColor: slotProps.data.category.color + '20',
                  color: slotProps.data.category.color,
                  borderColor: slotProps.data.category.color
                }"
              />
            </template>
          </Column>
          <Column field="reading_time" header="Время" style="width: 100px">
            <template #body="slotProps">
              {{ slotProps.data.reading_time }} мин
            </template>
          </Column>
          <Column field="view_count" header="Просмотры" style="width: 100px">
            <template #body="slotProps">
              {{ slotProps.data.view_count }}
            </template>
          </Column>
          <Column field="status" header="Статус" style="width: 140px">
            <template #body="slotProps">
              <div class="flex flex-col gap-1">
                <Tag 
                  :value="slotProps.data.is_published ? 'Опубликована' : 'Черновик'" 
                  :severity="slotProps.data.is_published ? 'success' : 'secondary'" 
                />
                <Tag 
                  v-if="slotProps.data.is_featured"
                  value="Рекомендуемая" 
                  severity="warning"
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
                  @click="editArticle(slotProps.data)"
                  v-tooltip="'Редактировать'"
                />
                <Button 
                  icon="pi pi-trash" 
                  text rounded 
                  severity="danger"
                  @click="deleteArticle(slotProps.data)"
                  v-tooltip="'Удалить'"
                />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <!-- Диалог создания/редактирования статьи -->
    <Dialog 
      v-model:visible="showCreateDialog" 
      modal 
      :header="editingArticle ? 'Редактировать статью' : 'Новая статья'"
      :style="{ width: '90vw', maxWidth: '1200px' }"
      :breakpoints="{ '960px': '95vw', '641px': '100vw' }"
    >
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Основная информация -->
        <div class="lg:col-span-2 flex flex-col gap-4">
          <div class="field">
            <label class="font-medium">Заголовок статьи *</label>
            <InputText 
              v-model="articleForm.title" 
              class="w-full" 
              placeholder="Введите заголовок..."
            />
          </div>

          <div class="field">
            <label class="font-medium">URL slug *</label>
            <InputText 
              v-model="articleForm.slug" 
              class="w-full" 
              placeholder="url-slug"
            />
          </div>

          <div class="field">
            <label class="font-medium">Краткое описание</label>
            <Textarea 
              v-model="articleForm.excerpt" 
              class="w-full" 
              rows="3"
              placeholder="Краткое описание статьи..."
            />
          </div>

          <div class="field">
            <label class="font-medium">Содержание статьи *</label>
            <Editor 
              v-model="articleForm.content" 
              editorStyle="height: 320px"
              :modules="editorModules"
            />
          </div>
        </div>

        <!-- Боковая панель -->
        <div class="flex flex-col gap-4">
          <!-- Публикация -->
          <Card>
            <template #title>Публикация</template>
            <template #content>
              <div class="flex flex-col gap-3">
                <div class="field-checkbox">
                  <Checkbox 
                    v-model="articleForm.is_published" 
                    :binary="true" 
                    inputId="is_published"
                  />
                  <label for="is_published" class="ml-2">Опубликовать</label>
                </div>
                <div class="field-checkbox">
                  <Checkbox 
                    v-model="articleForm.is_featured" 
                    :binary="true" 
                    inputId="is_featured"
                  />
                  <label for="is_featured" class="ml-2">Рекомендуемая</label>
                </div>
                <div class="field">
                  <label class="font-medium text-sm">Дата публикации</label>
                  <Calendar 
                    v-model="articleForm.published_at" 
                    class="w-full"
                    dateFormat="dd.mm.yy"
                  />
                </div>
              </div>
            </template>
          </Card>

          <!-- Категория и теги -->
          <Card>
            <template #title>Категория и теги</template>
            <template #content>
              <div class="flex flex-col gap-3">
                <div class="field">
                  <label class="font-medium text-sm">Категория *</label>
                  <Dropdown 
                    v-model="articleForm.category_id" 
                    :options="categories" 
                    optionLabel="name"
                    placeholder="Выберите категорию"
                    class="w-full"
                  />
                </div>
                <div class="field">
                  <label class="font-medium text-sm">Теги</label>
                  <MultiSelect 
                    v-model="articleForm.tag_ids" 
                    :options="tags" 
                    optionLabel="name"
                    placeholder="Выберите теги"
                    class="w-full"
                    :maxSelectedLabels="3"
                  />
                </div>
              </div>
            </template>
          </Card>

          <!-- Мета-информация -->
          <Card>
            <template #title>Мета-данные</template>
            <template #content>
              <div class="flex flex-col gap-3">
                <div class="field">
                  <label class="font-medium text-sm">Время чтения (мин)</label>
                  <InputNumber 
                    v-model="articleForm.reading_time" 
                    class="w-full"
                    :min="1"
                    :max="60"
                  />
                </div>
                <div class="field">
                  <label class="font-medium text-sm">Обложка</label>
                  <FileUpload 
                    mode="basic"
                    chooseLabel="Выбрать изображение"
                    class="w-full"
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
          :label="editingArticle ? 'Обновить статью' : 'Создать статью'" 
          @click="saveArticle" 
          :disabled="!articleForm.title || !articleForm.content"
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
const editingArticle = ref(null)

// Фильтры
const filters = reactive({
  category: null,
  status: null,
  search: ''
})

// Форма статьи
const articleForm = reactive({
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  category_id: null,
  tag_ids: [],
  reading_time: 5,
  cover_image: '',
  is_published: false,
  is_featured: false,
  published_at: null
})

// Mock данные
const articles = ref([
  {
    id: 1,
    title: 'Введение в Vue 3 Composition API',
    slug: 'vue-3-composition-api-intro',
    excerpt: 'Подробное руководство по новому Composition API во Vue 3',
    content: '<p>Полное содержание статьи...</p>',
    category: { id: 1, name: 'Vue.js', color: '#3B82F6' },
    reading_time: 8,
    view_count: 1542,
    is_published: true,
    is_featured: true,
    published_at: '2024-01-15',
    tags: [{ id: 1, name: 'Vue.js' }, { id: 2, name: 'JavaScript' }]
  },
  {
    id: 2,
    title: 'Nuxt 3: Полное руководство',
    slug: 'nuxt-3-complete-guide', 
    excerpt: 'Все что нужно знать о Nuxt 3',
    content: '<p>Содержание статьи про Nuxt 3...</p>',
    category: { id: 2, name: 'Nuxt', color: '#10B981' },
    reading_time: 12,
    view_count: 892,
    is_published: true,
    is_featured: false,
    published_at: '2024-01-10',
    tags: [{ id: 2, name: 'Nuxt' }]
  }
])

const categories = ref([
  { id: 1, name: 'Vue.js', color: '#3B82F6' },
  { id: 2, name: 'Nuxt', color: '#10B981' },
  { id: 3, name: 'JavaScript', color: '#F59E0B' }
])

const tags = ref([
  { id: 1, name: 'Vue.js' },
  { id: 2, name: 'Nuxt' },
  { id: 3, name: 'JavaScript' },
  { id: 4, name: 'TypeScript' },
  { id: 5, name: 'Composition API' }
])

const statusOptions = ref([
  'Все',
  'Опубликованные', 
  'Черновики'
])

// Вычисляемые свойства
const filteredArticles = computed(() => {
  let filtered = articles.value

  if (filters.category) {
    filtered = filtered.filter(article => article.category.id === filters.category.id)
  }

  if (filters.status === 'Опубликованные') {
    filtered = filtered.filter(article => article.is_published)
  } else if (filters.status === 'Черновики') {
    filtered = filtered.filter(article => !article.is_published)
  }

  if (filters.search) {
    const searchLower = filters.search.toLowerCase()
    filtered = filtered.filter(article => 
      article.title.toLowerCase().includes(searchLower) ||
      article.excerpt.toLowerCase().includes(searchLower)
    )
  }

  return filtered
})

// Методы
const editArticle = (article) => {
  editingArticle.value = article
  Object.assign(articleForm, {
    ...article,
    category_id: article.category.id,
    tag_ids: article.tags.map(tag => tag.id)
  })
  showCreateDialog.value = true
}

const deleteArticle = (article) => {
  if (confirm(`Удалить статью "${article.title}"?`)) {
    articles.value = articles.value.filter(a => a.id !== article.id)
  }
}

const saveArticle = () => {
  if (editingArticle.value) {
    // Обновляем статью
    const index = articles.value.findIndex(a => a.id === editingArticle.value.id)
    const category = categories.value.find(c => c.id === articleForm.category_id)
    const selectedTags = tags.value.filter(tag => articleForm.tag_ids.includes(tag.id))
    
    articles.value[index] = {
      ...articleForm,
      id: editingArticle.value.id,
      category,
      tags: selectedTags
    }
  } else {
    // Создаем новую статью
    const category = categories.value.find(c => c.id === articleForm.category_id)
    const selectedTags = tags.value.filter(tag => articleForm.tag_ids.includes(tag.id))
    
    const newArticle = {
      ...articleForm,
      id: Date.now(),
      view_count: 0,
      category,
      tags: selectedTags
    }
    articles.value.unshift(newArticle)
  }
  
  closeDialog()
}

const closeDialog = () => {
  showCreateDialog.value = false
  editingArticle.value = null
  Object.assign(articleForm, {
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    category_id: null,
    tag_ids: [],
    reading_time: 5,
    cover_image: '',
    is_published: false,
    is_featured: false,
    published_at: null
  })
}

// Настройки редактора (упрощенные)
const editorModules = ref([
  // В реальном проекте можно добавить Quill модули
])
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