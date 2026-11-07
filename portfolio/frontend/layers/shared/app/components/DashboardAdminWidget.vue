<template>
      <div>
    <!-- Статистика -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <StatCard title="Статей" :count="16" subtitle="Опубликовано: 12" icon="ph:file-text-bold" color="rose" />
      <StatCard title="Проектов" :count="8" subtitle="Завершено: 5" icon="ph:briefcase-bold" color="lime" />
      <StatCard title="Категорий" :count="12" subtitle="Активно: 8" icon="ph:folder-bold" color="violet" />
      <StatCard title="Тегов" :count="24" subtitle="Использовано: 18" icon="ph:tag-bold" color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

      <RecentArticlesWidget />
      <ActiveProjectsWidget />

    </div>
  </div>
</template>
