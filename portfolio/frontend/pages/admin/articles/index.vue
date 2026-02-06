<template>
  <h2 class="text-3xl">Hello Articles</h2>
</template>

<script setup lang="ts">
const route = useRoute()

definePageMeta({
  layout: 'admin'
})

const { data } = useFetch('http://localhost/api/articles', {
  server: false
});

</script>

<style scoped>
</style>