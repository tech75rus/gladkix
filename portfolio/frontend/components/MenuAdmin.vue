<template>
    <nav class="hidden md:flex flex flex-col space-y-4 py-4 bg-white rounded-lg shadow-md transition-all duration-300 overflow-hidden"
      :class="sidebar ? 'w-[220px] px-4' : 'w-16 px-2'"
    >
      <NuxtLink 
        to="/admin" 
        class="font-bold text-custom-200 flex items-center p-3 rounded-lg hover:bg-custom-20 transition-colors duration-200"
      >
        <Icon name="ph:crown" size="26" class="w-6 h-6 shrink-0"/>
        <span v-if="sidebar" class="mx-4 transition-all duration-300 whitespace-nowrap overflow-hidden"
          :class="sidebar ? 'opacity-100 max-w-[200px]' : 'opacity-0 max-w-0'"
        >
          Admin
        </span>
      </NuxtLink>
      
      <ul class="flex flex-col space-y-1">
        <li v-for="item in menuItems" :key="item.to">
          <NuxtLink 
            :to="item.to" 
            class="flex items-center p-3 rounded-lg text-custom-600 hover:bg-custom-600 transition-colors duration-200"
            :class="{ 'bg-custom-50': isActive(item.to) }"
          >
            <Icon :name="item.icon" size="26" class="w-6 h-6 shrink-0"/>
            <span v-if="sidebar" class="mx-4 transition-all duration-300 whitespace-nowrap overflow-hidden"
              :class="sidebar ? 'opacity-100 max-w-[120px]' : 'opacity-0 max-w-0'"
            >
              {{ item.label }}
            </span>
          </NuxtLink>
        </li>
      </ul>
      <!-- Кнопка прижата вниз и вправо -->
      <div class="flex items-end justify-end h-[100%]">
        <Button
          class="p-3 bg-transparent text-custom-500 border-transparent rounded-lg transition-transform duration-300"
          @click="clickSidebar"
        >
          <Icon 
            :name=" sidebar ? 'ph:square-half' : 'ph:sidebar'" 
            size="26" class="w-6 h-6 shrink-0"
          />
        </Button>
      </div>
    </nav>    
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

// Функция проверки активного пути
const isActive = (path) => {
    return route.path.startsWith(path)
}

const clickSidebar = () => {
  sidebar.value = !sidebar.value;
}

// Закрытие меню при изменении маршрута
watch(() => route.path, () => {
  if (menuOpen.value) {
    closeMenu()
  }
})

</script>

<style scoped>

</style>