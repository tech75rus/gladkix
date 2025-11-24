<template>
    <nav class="flex flex-col p-4 space-y-4 bg-white rounded-lg shadow-md">
      <NuxtLink 
        to="/admin" 
        class="text-xl font-bold text-custom-11 flex items-center p-3 pr-20 rounded-lg hover:bg-custom-20 transition-colors duration-200"
        :class="{ 'bg-custom-20': isActive('/admin') }"
      >
        <Icon name="ph:crown" class="mr-3 text-lg" />
        Admin
      </NuxtLink>
      
      <ul class="flex flex-col space-y-1">
        <li v-for="item in menuItems" :key="item.to">
          <NuxtLink 
            :to="item.to" 
            class="flex items-center p-3 rounded-lg hover:bg-custom-600 transition-colors duration-200"
            :class="{ 'bg-custom-50': isActive(item.to) }"
          >
            <Icon :name="item.icon" class="mr-3" />
            {{ item.label }}
          </NuxtLink>
        </li>
      </ul>
    </nav>
</template>

<script setup>
// Реактивные данные меню
const menuItems = [
  { to: '/admin/articles', icon: 'ph:article', label: 'Articles' },
  { to: '/admin/projects', icon: 'ph:folder', label: 'Projects' },
  { to: '/admin/tags', icon: 'ph:tag', label: 'Tags' },
  { to: '/admin/categories', icon: 'ph:folders', label: 'Categories' }
]

const route = useRoute()

// Функция проверки активного пути
const isActive = (path) => {
  return route.path.startsWith(path)
}
</script>

<style scoped>

</style>