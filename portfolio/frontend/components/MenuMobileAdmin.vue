<template>
  <div class="fixed md:hidden z-[9999]">
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
      class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-[9999] transition-transform duration-300"
      :style="{ transform: `translateX(${menuPosition}px)` }"
      @pointerdown="handlePointerDown"
      @touchmove="handlePointerMove"
      @pointerup="handlePointerUp"
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
const sidebar = ref(true);
const route = useRoute()

// Переменные для перетаскивания
const isDragging = ref(false)
const startX = ref(0)
const currentX = ref(0)
const menuPosition = ref(-256) // Начальная позиция (скрыто)
const menuWidth = 356 // Ширина меню

// Функция проверки активного пути
const isActive = (path) => {
    return route.path.startsWith(path)
}

const clickSidebar = () => {
  sidebar.value = !sidebar.value;
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
const handlePointerDown = (e) => {
  isDragging.value = true
  startX.value = e.clientX
  currentX.value = menuPosition.value
}

const handlePointerMove = (e) => {
  if (!isDragging.value) return
  
  const deltaX = e.touches[0].clientX - startX.value
  let newPosition = currentX.value + deltaX
  
  // Ограничиваем движение в пределах от -menuWidth до 0
  newPosition = Math.max(-menuWidth, Math.min(0, newPosition))
  menuPosition.value = newPosition
}

const handlePointerUp = () => {
  if (!isDragging.value) return
  
  isDragging.value = false
  
  // Определяем, нужно ли закрыть или открыть меню based on position
  const threshold = menuWidth * 0.3 // 30% порог
  
  if (menuPosition.value > -threshold) {
    // Открываем меню
    menuOpen.value = true
    menuPosition.value = 0
  } else {
    // Закрываем меню
    menuOpen.value = false
    menuPosition.value = -menuWidth
  }
}

const handlePointerCancel = () => {
  isDragging.value = false
  // Возвращаем в исходное состояние при отмене
  if (menuOpen.value) {
    menuPosition.value = 0
  } else {
    menuPosition.value = -menuWidth
  }
}

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
  touch-action: pan-y;
  user-select: none;
}
</style>