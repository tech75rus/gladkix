<template>
  <div class="container">
    <h1>Мой проект с Tiptap</h1>
    
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
</template>

<script setup>
import { ref } from 'vue'

const count = ref(0)
const testColor = ref('#3B82F6')

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