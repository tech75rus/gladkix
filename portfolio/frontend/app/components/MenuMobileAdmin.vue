<template>
  <div class="fixed z-[9999] touch-none">
    <Button label="Secondary" severity="secondary" raised class="mr-2 mb-2 bg-white" @click="openMenu">
      <Icon name="ph:square-half" size="26" class="w-6 h-6 shrink-0"/>
    </Button>

    <!-- Оверлей -->
    <div 
      v-if="menuOpen"
      class="fixed inset-0 bg-black bg-opacity-50 z-[9999] transition-opacity duration-300"
      @click="closeMenu"
    ></div>

    <div 
      class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-[9999]"
      :class="{
        'transition-transform duration-300': !isDragging, // Плавно когда не тащим
        'transition-none': isDragging // Без анимации когда тащим
      }"
      :style="{ transform: `translateX(${menuPosition}px)` }"
      @pointerdown="startDrag"
      @pointermove="handlerDrag"
      @pointerup="stopDrag"
      @pointercancel="stopDrag"
    >
      <nav class="flex flex-col space-y-4 py-4 px-4">
        <NuxtLink 
          to="/admin" 
          class="font-bold text-custom-200 flex items-center p-3 rounded-lg hover:bg-custom-20 transition-colors duration-200"
        >
          <Icon name="ph:crown" size="26" class="w-6 h-6 shrink-0"/>
          <span class="mx-4">Admin</span>
        </NuxtLink>
        <ul class="flex flex-col space-y-1">
          <li v-for="item in menuItems" :key="item.to">
            <NuxtLink 
              :to="item.to"
              class="flex items-center p-3 rounded-lg text-custom-600 hover:bg-custom-600 transition-colors duration-200"
              :class="{ 'bg-custom-50': isActive(item.to) }"
            >
              <Icon :name="item.icon" size="26" class="w-6 h-6 shrink-0"/>
              <span class="mx-4">
                {{ item.label }}
              </span>
            </NuxtLink>
          </li>
        </ul>
      </nav>
    </div>

  </div>
    
</template>

<script setup>
// Реактивные данные меню
const menuItems = [
  { to: '/admin/articles', icon: 'ph:article', label: 'Articles' },
  { to: '/admin/categories', icon: 'ph:folders', label: 'Categories' },
  { to: '/admin/tags', icon: 'ph:tag', label: 'Tags' },
  { to: '/admin/projects', icon: 'ph:folder', label: 'Projects' },
]

const menuOpen = ref(false);
const route = useRoute()

// Переменные для перетаскивания
const menuPosition = ref(-256) // Начальная позиция (скрыто)
const menuWidth = 256 // Ширина меню

const isDragging = ref(false)
const startPosition = ref(0);
const startX = ref(0)
const currentX = ref(0)

let rafId = null;

// Функция проверки активного пути
const isActive = (path) => {
    return route.path.startsWith(path)
}

const openMenu = () => {
  menuOpen.value = true
  menuPosition.value = 0
}

const closeMenu = () => {
  menuOpen.value = false
  menuPosition.value = -menuWidth
}

// Pointer events для плавного перетаскивания
const startDrag = (e) => {
  isDragging.value = true;
  startX.value = e.clientX;
  startPosition.value = menuPosition.value;

  e.currentTarget.setPointerCapture(e.pointerId)
}

const handlerDrag = (e) => {  
  if (!isDragging.value) return
  
  if (rafId) {
    cancelAnimationFrame(rafId)
  }

  rafId = requestAnimationFrame(() => {
    const deltaX = e.clientX - startX.value;
    let newPosition = startPosition.value + deltaX;

    // ⚡ ОГРАНИЧИВАЕМ ДВИЖЕНИЕ: от -256px до 0px
    newPosition = Math.max(-menuWidth, Math.min(0, newPosition));

    menuPosition.value = newPosition;
    rafId = null;
  })
}

const stopDrag = () => {
  isDragging.value = false
  
  // ⚡ ЛОГИКА АВТОМАТИЧЕСКОГО ЗАКРЫТИЯ/ОТКРЫТИЯ
  const threshold = menuWidth * 0.3; // 30% от ширины = 77px

  if (menuPosition.value > -threshold) {
    // Меню открыто больше чем на 70% - оставляем открытым
    menuOpen.value = true
    menuPosition.value = 0
  } else {
    // Меню открыто меньше чем на 70% - закрываем
    menuOpen.value = false
    menuPosition.value = -menuWidth
  }

  if (rafId) {
    cancelAnimationFrame(rafId)
    rafId = null
  }
}

onUnmounted(() => {
  if (rafId) {
    cancelAnimationFrame(rafId);
  }
})

// Закрытие меню при изменении маршрута
watch(() => route.path, () => {
  if (menuOpen.value) {
    closeMenu()
  }
})

</script>

<style scoped>
/* Улучшаем производительность анимаций */
nav {
  will-change: transform;
  user-select: none;
  -webkit-user-select: none;
  backface-visibility: hidden;
  touch-action: none; /* ⚡ САМОЕ ВАЖНОЕ! */
  -webkit-touch-callout: none;
}
</style>