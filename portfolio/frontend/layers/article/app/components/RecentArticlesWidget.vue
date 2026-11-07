<template>
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
            <Button class="p-3"
            text rounded 
            @click="$router.push(`/admin/articles`)"
            >
            <Icon name="ph:arrow-right" size="22"></Icon>
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

</template>

<script lang="ts" setup>
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

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('ru-RU')
}

</script>
