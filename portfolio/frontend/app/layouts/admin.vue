<template>
  <div class="bg-custom-10 min-h-screen p-4 flex flex-row">
    <Toast />
    <MenuAdmin @close="open = $event" class="md:flex" />
    <MenuMobileAdmin class="md:hidden" />
    <Button raised severity="secondary" class="!fixed bottom-[17px] right-[15px] border z-50 bg-white" @click="toggleFullscreen">
      <Icon :name="isFullscreen ? 'ph:arrows-in' : 'ph:arrows-out'" size="26" />
    </Button>
    <div class="flex-1 transition-all duration-300 max-md:mt-[50px] mb-[70px]"
      :class="[open ? 'md:ml-[235px]' : 'md:ml-[80px]']"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script setup>

const open = ref(true);

const isFullscreen = ref(false);

// Функция переключения
const toggleFullscreen = () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen();
  } else {
    document.exitFullscreen();
  }
};

// Слушаем изменение статуса
const handleFullscreenChange = () => {
  isFullscreen.value = !!document.fullscreenElement;
};

onMounted(() => {
  document.addEventListener('fullscreenchange', handleFullscreenChange);
});

onUnmounted(() => {
  document.removeEventListener('fullscreenchange', handleFullscreenChange);
});

</script>
