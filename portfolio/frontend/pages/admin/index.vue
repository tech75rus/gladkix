<template>
  <div class="m-6">
    <!-- Заголовок -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-surface-900">Дашборд</h1>
      <p class="text-surface-600">Обзор вашего портфолио</p>
    </div>

    <!-- Статистика -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <Card class="bg-rose-50 border border-rose-300">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-rose-400">{{ stats.articles.total }}</div>
              <div class="text-primary-600">Статей</div>
            </div>
            <Icon name="ph:file-bold" size="32px" class="text-rose-400"></Icon>
          </div>
          <div class="mt-2 text-sm text-surface-500">
            {{ stats.articles.published }} опубликовано
          </div>
        </template>
      </Card>

      <Card class="bg-lime-50 border border-lime-300">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-lime-400">{{ stats.projects.total }}</div>
              <div class="text-surface-600">Проектов</div>
            </div>
            <Icon name="ph:briefcase-bold" size="32px" class="text-lime-400"></Icon>
          </div>
          <div class="mt-2 text-sm text-surface-500">
            {{ stats.projects.completed }} завершено
          </div>
        </template>
      </Card>

      <Card class="bg-custom-50 border border-custom-300">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-custom-400">{{ stats.categories }}</div>
              <div class="text-surface-600">Категорий</div>
            </div>
            <Icon name="ph:folder-bold" size="32px" class="text-custom-400"></Icon>
          </div>
          <div class="mt-2 text-sm text-surface-500">
            {{ stats.categoriesVisible }} активно
          </div>
        </template>
      </Card>

      <Card class="bg-amber-50 border border-amber-300">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-amber-500">{{ stats.tags }}</div>
              <div class="text-surface-600">Тегов</div>
            </div>
            <Icon name="ph:tag-bold" size="32px" class="text-amber-500"></Icon>
          </div>
          <div class="mt-2 text-sm text-surface-500">
            Используется в проектах
          </div>
        </template>
      </Card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Последние статьи -->
      <Card>
        <template #title>Последние статьи</template>
        <template #content>
          <div class="space-y-4">
            <div 
              v-for="article in recentArticles" 
              :key="article.id"
              class="flex items-center gap-3 p-3 hover:bg-surface-50 rounded-lg transition-colors"
            >
              <img 
                :src="article.cover_image" 
                :alt="article.title"
                class="w-12 h-9 object-cover rounded"
              >
              <div class="flex-1 min-w-0">
                <div class="font-medium text-sm truncate">{{ article.title }}</div>
                <div class="flex items-center gap-2 text-xs text-surface-500">
                  <Tag 
                    :value="article.category.name" 
                    class="text-xs"
                    :style="{ 
                      backgroundColor: article.category.color + '20',
                      color: article.category.color,
                      borderColor: article.category.color
                    }"
                  />
                  <span>{{ article.reading_time }} мин</span>
                  <span>{{ formatDate(article.published_at) }}</span>
                </div>
              </div>
              <Button 
                text rounded 
                severity="secondary"
                @click="$router.push(`/admin/articles`)"
              >
                <Icon name="ph:arrow-right"></Icon>
              </Button>
            </div>
          </div>
          <div class="mt-4 text-center">
            <Button 
              label="Все статьи" 
              text 
              @click="$router.push('/admin/articles')"
            />
          </div>
        </template>
      </Card>

      <!-- Активные проекты -->
      <Card>
        <template #title>Активные проекты</template>
        <template #content>
          <div class="space-y-4">
            <div 
              v-for="project in activeProjects" 
              :key="project.id"
              class="flex items-center gap-3 p-3 hover:bg-surface-50 rounded-lg transition-colors"
            >
              <img 
                :src="project.cover_image" 
                :alt="project.title"
                class="w-12 h-12 object-cover rounded-lg"
              >
              <div class="flex-1 min-w-0">
                <div class="font-medium text-sm truncate">{{ project.title }}</div>
                <div class="flex items-center gap-2 text-xs text-surface-500">
                  <Tag 
                    :value="getStatusLabel(project.status)" 
                    :severity="getStatusSeverity(project.status)"
                    class="text-xs"
                  />
                  <span>{{ formatDate(project.updated_at) }}</span>
                </div>
              </div>
              <Button 
                icon="pi pi-arrow-right" 
                text rounded 
                severity="secondary"
                @click="$router.push(`/admin/projects`)"
              />
            </div>
          </div>
          <div class="mt-4 text-center">
            <Button 
              label="Все проекты" 
              text 
              @click="$router.push('/admin/projects')"
            />
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})

// Mock данные для статистики
const stats = reactive({
  articles: {
    total: 15,
    published: 12
  },
  projects: {
    total: 8,
    completed: 5
  },
  categories: 6,
  categoriesVisible: 4,
  tags: 24
})

// Последние статьи с реальными изображениями
const recentArticles = ref([
  {
    id: 1,
    title: 'Введение в Vue 3 Composition API',
    cover_image: 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=200&h=150&fit=crop', // Vue.js изображение
    category: { name: 'Vue.js', color: '#3B82F6' },
    reading_time: 8,
    published_at: '2024-01-15'
  },
  {
    id: 2,
    title: 'Nuxt 3: Полное руководство',
    cover_image: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=200&h=150&fit=crop', // Код программирования
    category: { name: 'Nuxt', color: '#10B981' },
    reading_time: 12,
    published_at: '2024-01-10'
  },
  {
    id: 3,
    title: 'TypeScript для начинающих',
    cover_image: 'https://images.unsplash.com/photo-1516116216624-53e697fedbea?w=200&h=150&fit=crop', // Еще код
    category: { name: 'TypeScript', color: '#3178C6' },
    reading_time: 10,
    published_at: '2024-01-08'
  }
])

// Активные проекты с реальными изображениями
const activeProjects = ref([
  {
    id: 1,
    title: 'Портфолио сайт на Nuxt 3',
    cover_image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=200&h=200&fit=crop', // Веб-дизайн
    status: 'completed',
    updated_at: '2024-01-15'
  },
  {
    id: 2,
    title: 'E-commerce платформа',
    cover_image: 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=200&h=200&fit=crop', // Электронная коммерция
    status: 'in_progress',
    updated_at: '2024-01-20'
  },
  {
    id: 3,
    title: 'Мобильное приложение',
    cover_image: 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=200&h=200&fit=crop', // Мобильные телефоны
    status: 'planning',
    updated_at: '2024-01-18'
  }
])

// Методы
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ru-RU')
}

const getStatusLabel = (status) => {
  const labels = {
    planning: 'Планирование',
    in_progress: 'В работе',
    completed: 'Завершен',
    on_hold: 'На паузе'
  }
  return labels[status] || status
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
</script>