<template>
  <div class="grid grid-cols-1 bg-white shadow-md rounded-lg">
    <DataTable :value="articles" paginator :rows="12" class="whitespace-nowrap">
      <Column field="id" header="Code"></Column>
      <Column field="title" header="Name"></Column>
      <Column field="createdAt" header="Category"></Column>
      <Column field="readingTime" header="Quantity"></Column>
    </DataTable>
  </div>
</template>

<script setup lang="ts">
import type { Article } from '~/types/Article';

const route = useRoute()

definePageMeta({
  layout: 'admin'
})

const { data: articles } = useFetch<Article[]>('/backend/articles', {
  server: false
});

</script>

<style scoped>
@media (max-width: 500px) {
  :deep(.p-paginator) {
    padding: 7px 0;
  }
  :deep(.p-paginator-content) {
    font-size: .85rem;
  }
  :deep(.p-paginator-content > button) {
    /* font-size: .75rem;      Уменьшаем размер шрифта */
    padding: 0.25rem 0.5rem; /* Уменьшаем внутренние отступы */
    min-width: 2rem;         /* Уменьшаем минимальную ширину */
  }
}
@media (max-width: 400px) {
  :deep(.p-paginator) {
    padding: 7px 0;
  }
  :deep(.p-paginator-content) {
    font-size: .85rem;
  }
  :deep(.p-paginator-content > button) {
    /* font-size: .75rem;      Уменьшаем размер шрифта */
    padding: 0.1rem 0.2rem; /* Уменьшаем внутренние отступы */
    min-width: 1.3rem;         /* Уменьшаем минимальную ширину */
  }
}
</style>