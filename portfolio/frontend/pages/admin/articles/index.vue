<template>
  <div class="flex p-8 border ml-4 h-[100%]">
    <div 
      class="bg-emerald-200 transition-all duration-300"
      :class="size ? 'w-[600px]' : 'w-[300px]'"
    >One</div>
    <div class="bg-orange-200 w-full">Two</div>
  </div>
  <div class="fixed bottom-2 right-8">
    <Button @click="changeSize">Click</Button>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})

const size = ref(false);

const changeSize = () => {
  size.value = !size.value;
}

</script>

<style scoped>
</style>