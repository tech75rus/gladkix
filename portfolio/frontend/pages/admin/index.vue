<template>
  <div>
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-surface-900">Дашборд</h1>
      <p class="text-surface-600">Обзор вашего портфолио</p>
    </div>

    <!-- Статистика -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <Card class="p-4">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-primary">0</div>
              <div class="text-surface-600">Статей</div>
            </div>
            <i class="pi pi-file text-3xl text-primary opacity-60"></i>
          </div>
        </template>
      </Card>

      <Card class="p-4">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-green-600">0</div>
              <div class="text-surface-600">Проектов</div>
            </div>
            <i class="pi pi-briefcase text-3xl text-green-600 opacity-60"></i>
          </div>
        </template>
      </Card>

      <Card class="p-4">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-purple-600">0</div>
              <div class="text-surface-600">Категорий</div>
            </div>
            <i class="pi pi-tags text-3xl text-purple-600 opacity-60"></i>
          </div>
        </template>
      </Card>

      <Card class="p-4">
        <template #content>
          <div class="flex justify-between items-center">
            <div>
              <div class="text-2xl font-bold text-orange-600">0</div>
              <div class="text-surface-600">Тегов</div>
            </div>
            <i class="pi pi-hashtag text-3xl text-orange-600 opacity-60"></i>
          </div>
        </template>
      </Card>
    </div>

    <!-- Быстрые действия -->
    <Card>
      <template #title>Быстрые действия</template>
      <template #content>
        <div class="flex flex-wrap gap-4">
          <Button label="Новая статья" icon="pi pi-plus" severity="primary" />
          <Button label="Новый проект" icon="pi pi-plus" severity="secondary" />
          <Button label="Новая категория" icon="pi pi-plus" severity="success" />
          <Button label="Новый тег" icon="pi pi-plus" severity="help" />
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})
</script>