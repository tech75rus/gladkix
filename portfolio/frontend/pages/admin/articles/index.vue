<template>
  <h2 class="text-3xl">Hello Articles</h2>

  <DataTable :value="articles" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" >
    <Column field="title" header="Описание" style="width: 25%"></Column>
    <Column field="excerpt" header="Короткое описание" style="width: 25%"></Column>
    <Column field="createdAt" header="Создан" style="width: 25%"></Column>
    <Column field="readingTime" header="Время чтения" style="width: 25%"></Column>
  </DataTable>

</template>

<script setup lang="ts">
import type { Article } from '~/types/Article';

const route = useRoute()

definePageMeta({
  layout: 'admin'
})

const { data: articles } = useFetch<Article[]>('http://localhost/api/articles', {
  server: false
});

</script>

<style scoped>
</style>