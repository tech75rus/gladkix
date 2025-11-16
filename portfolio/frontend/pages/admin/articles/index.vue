<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-surface-900">Статьи</h1>
        <p class="text-surface-600">Управление статьями блога</p>
      </div>
      <Button label="Новая статья" icon="pi pi-plus" />
    </div>

    <Card>
      <template #content>
        <DataTable :value="articles" paginator :rows="10">
          <Column field="id" header="ID" style="width: 80px"></Column>
          <Column field="title" header="Заголовок"></Column>
          <Column field="category" header="Категория" style="width: 150px"></Column>
          <Column field="status" header="Статус" style="width: 120px">
            <template #body="slotProps">
              <Tag 
                :value="slotProps.data.status" 
                :severity="slotProps.data.status === 'Опубликована' ? 'success' : 'secondary'" 
              />
            </template>
          </Column>
          <Column header="Действия" style="width: 120px">
            <template #body>
              <div class="flex gap-2">
                <Button icon="pi pi-pencil" text rounded severity="warning" />
                <Button icon="pi pi-trash" text rounded severity="danger" />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})

const articles = ref([
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
  { id: 2, title: 'Вторая статья', category: 'Nuxt', status: 'Черновик' },
  { id: 1, title: 'Первая статья', category: 'Vue.js', status: 'Опубликована' },
])
</script>