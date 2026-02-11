<template>
  <div class="bg-custom-10 min-h-screen p-4 flex flex-row">
    <Toast />
    <MenuAdmin @close="open = $event" class="md:flex" />
    <MenuMobileAdmin class="md:hidden" />
    <div class="flex-1"
      :class="[open ? 'md:ml-[235px]' : 'md:ml-[80px]']"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script setup>

const open = ref(true);

</script>
