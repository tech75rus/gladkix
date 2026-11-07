<template>
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
                        :value="getStatusLabel(project.status as ProjectStatus)" 
                        :severity="getStatusSeverity(project.status as ProjectStatus)"
                        class="text-xs"
                    />
                    <span>{{ formatDate(project.updated_at) }}</span>
                </div>
                </div>
                <Button class="p-3"
                    text rounded 
                    @click="$router.push(`/admin/projects`)"
                >
                    <Icon name="ph:arrow-right" size="22"></Icon>
                </Button>
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
</template>

<script lang="ts" setup>

type ProjectStatus = 'planning' | 'in_progress' | 'completed' | 'on_hold';

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

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('ru-RU')
}

const getStatusLabel = (status: ProjectStatus) => {
  const labels: Record<ProjectStatus, string> = {
    planning: 'Планирование',
    in_progress: 'В работе',
    completed: 'Завершен',
    on_hold: 'На паузе'
  }
  return labels[status] || status
}

const getStatusSeverity = (status: ProjectStatus) => {
  const severities: Record<ProjectStatus, string> = {
    planning: 'secondary',
    in_progress: 'warning',
    completed: 'success',
    on_hold: 'danger'
  }
  return severities[status] || 'info'
}
</script>
