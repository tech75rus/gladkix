<template>
    <nav class="hidden md:flex flex flex-col p-4 space-y-4 bg-white rounded-lg shadow-md">
      <NuxtLink 
        to="/admin" 
        class="text-xl font-bold text-custom-200 flex items-center p-3 rounded-lg hover:bg-custom-20 transition-colors duration-200"
      >
        <Icon name="ph:crown" size="26"/>
        <span v-if="sidebar" class="mx-4">Admin</span>
      </NuxtLink>
      
      <ul class="flex flex-col space-y-1">
        <li v-for="item in menuItems" :key="item.to">
          <NuxtLink 
            :to="item.to" 
            class="flex items-center p-3 rounded-lg text-custom-600 hover:bg-custom-600 transition-colors duration-200"
            :class="{ 'bg-custom-50': isActive(item.to) }"
          >
            <Icon :name="item.icon" size="26"/>
            <span v-if="sidebar" class="mx-4">{{ item.label }}</span>
          </NuxtLink>
        </li>
      </ul>
      <!-- Кнопка прижата вниз и вправо -->
      <div class="flex items-end justify-end h-[100%]"
        @click="clickSidebar"
      >
        <Button
          class="p-3 bg-transparent text-custom-500 border-transparent rounded-lg"
        >
          <Icon name="ph:sidebar" size="26"/>
        </Button>
      </div>
    </nav>
    <nav class="">

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

const sidebar = ref(true);
const route = useRoute()

// Функция проверки активного пути
const isActive = (path) => {
    return route.path.startsWith(path)
}

const clickSidebar = () => {
  sidebar.value = !sidebar.value;
}
</script>

<style scoped>

</style>