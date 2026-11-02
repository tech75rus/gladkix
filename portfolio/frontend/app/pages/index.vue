<template>
  <div class="container">
    <h1>Мой проект с Tiptap</h1>

    <Splitter style="height: 300px">
        <SplitterPanel class="flex items-center justify-center"> Panel 1 </SplitterPanel>
        <SplitterPanel class="flex items-center justify-center"> Panel 2 </SplitterPanel>
    </Splitter>

    <Card class="test" style="width: 25rem; overflow: hidden">
      <template #header>
          <img alt="user header" src="/images/usercard.jpeg" />
      </template>
      <template #title>Advanced Card</template>
      <template #subtitle>Card subtitle</template>
      <template #content>
          <p class="m-0">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore sed consequuntur error repudiandae numquam deserunt quisquam repellat libero asperiores earum nam nobis, culpa ratione quam perferendis esse, cupiditate neque
              quas!
          </p>
      </template>
      <template #footer>
          <div class="flex gap-4 mt-1">
              <Button label="Cancel" severity="secondary" variant="outlined" class="w-full" />
              <Button label="Save" class="w-full" />
          </div>
      </template>
    </Card>

    <TiptapEditor />

    <!-- Демонстрация, что остальные компоненты работают -->
    <div class="demo-section">
      <h2>Другие компоненты</h2>
      <Button label="Test Button" @click="checkClick" />
      <p>Счетчик: {{ count }}</p>
      
      <p>Выбранный цвет: {{ testColor }}</p>
      <ColorPicker v-model="testColor" />
    </div>

    <Button icon="pi pi-file" label="Тест" class="p-button-secondary" />
    <Button icon="pi pi-pencil" label="Тест" class="p-button-secondary" />


  </div>
  <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 
              md:top-0 md:left-0 md:right-auto md:bottom-auto 
              md:w-64 md:border-t-0 md:border-r md:h-screen">
    
    <div class="flex justify-around items-center h-16 
                md:flex-col md:items-start md:justify-start md:p-4 md:space-y-2">
      
      <button v-for="item in menuItems" :key="item.id" 
              class="flex flex-col items-center p-2 md:flex-row md:w-full md:justify-start 
                    md:p-3 md:rounded-lg md:hover:bg-gray-100">
        <Icon :name="item.icon" size="24px"></Icon>
        <span class="text-xs mt-1 md:mt-0 md:ml-3 md:text-sm">{{ item.label }}</span>
      </button>
      
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'

const count = ref(0)
const testColor = ref('#3B82F6')
const menuItems = [
  {
    id: 1,
    label: 'Home',
    icon: 'ph:house'
  },
  {
    id: 2,
    label: 'Profile',
    icon: 'ph:user'
  },
  {
    id: 3,
    label: 'Settings',
    icon: 'ph:gear-six'
  },
  {
    id: 4,
    label: 'Logout',
    icon: 'ph:sign-out'
  }
]

const checkClick = () => {
  count.value++
  console.log('Кнопка нажата!')
}
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.test {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.demo-section {
  margin: 40px 0;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: rgb(255, 255, 255);
}

.demo-section h2 {
  color: #374151;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #f3f4f6;
}
</style>