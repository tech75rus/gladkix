<template>
  <div>
    <!-- Заголовок и кнопка создания -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-surface-900">Категории</h1>
        <p class="text-surface-600">Управление категориями статей</p>
      </div>
      <Button 
        label="Новая категория" 
        icon="pi pi-plus" 
        @click="showCreateDialog = true"
      />
    </div>

    <!-- Таблица категорий -->
    <Card>
      <template #content>
        <DataTable :value="categories" paginator :rows="10">
          <Column field="id" header="ID" style="width: 80px"></Column>
          <Column field="name" header="Название"></Column>
          <Column field="slug" header="URL"></Column>
          <Column field="item_count" header="Статей" style="width: 100px">
            <template #body="slotProps">
              <Tag :value="slotProps.data.item_count" />
            </template>
          </Column>
          <Column field="is_visible" header="Видимость" style="width: 120px">
            <template #body="slotProps">
              <Tag 
                :value="slotProps.data.is_visible ? 'Включено' : 'Выключено'" 
                :severity="slotProps.data.is_visible ? 'success' : 'secondary'" 
              />
            </template>
          </Column>
          <Column header="Действия" style="width: 120px">
            <template #body="slotProps">
              <div class="flex gap-2">
                <Button 
                  icon="pi pi-pencil" 
                  text rounded 
                  severity="warning"
                  @click="editCategory(slotProps.data)"
                />
                <Button 
                  icon="pi pi-trash" 
                  text rounded 
                  severity="danger"
                  @click="deleteCategory(slotProps.data)"
                />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <!-- Диалог создания/редактирования -->
    <Dialog 
      v-model:visible="showCreateDialog" 
      modal 
      :header="editingCategory ? 'Редактировать категорию' : 'Новая категория'"
      :style="{ width: '500px' }"
    >
      <div class="flex flex-col gap-4">
        <div class="field">
          <label for="name" class="font-medium">Название категории *</label>
          <InputText 
            id="name" 
            v-model="categoryForm.name" 
            class="w-full" 
            placeholder="Веб-разработка"
          />
        </div>

        <div class="field">
          <label for="slug" class="font-medium">URL slug *</label>
          <InputText 
            id="slug" 
            v-model="categoryForm.slug" 
            class="w-full" 
            placeholder="web-development"
          />
        </div>

        <div class="field">
          <label for="description" class="font-medium">Описание</label>
          <Textarea 
            id="description" 
            v-model="categoryForm.description" 
            class="w-full" 
            rows="3"
            placeholder="Описание категории..."
          />
        </div>

        <div class="field">
          <label for="color" class="font-medium">Цвет</label>
          <ColorPicker 
            id="color" 
            v-model="categoryForm.color" 
            class="w-full"
            format="hex"
          />
        </div>

        <div class="field">
          <label for="icon" class="font-medium">Иконка PrimeIcons</label>
          <InputText 
            id="icon" 
            v-model="categoryForm.icon" 
            class="w-full" 
            placeholder="pi pi-code"
          />
          <small class="text-surface-500">Например: pi-code, pi-palette, pi-server</small>
        </div>

        <div class="field-checkbox">
          <Checkbox 
            id="is_visible" 
            v-model="categoryForm.is_visible" 
            :binary="true" 
          />
          <label for="is_visible" class="ml-2">Категория видна на сайте</label>
        </div>
      </div>

      <template #footer>
        <Button 
          label="Отмена" 
          severity="secondary" 
          @click="closeDialog" 
        />
        <Button 
          :label="editingCategory ? 'Обновить' : 'Создать'" 
          @click="saveCategory" 
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
// Импортируем Textarea и ColorPicker
import Textarea from 'primevue/textarea'
import ColorPicker from 'primevue/colorpicker'
import Checkbox from 'primevue/checkbox'

// Регистрируем компоненты
definePageMeta({
  layout: 'admin'
})

// Состояние
const showCreateDialog = ref(false)
const editingCategory = ref(null)

// Форма категории
const categoryForm = reactive({
  name: '',
  slug: '',
  description: '',
  color: '#3B82F6',
  icon: 'pi pi-folder',
  is_visible: true
})

// Mock данные
const categories = ref([
  { 
    id: 1, 
    name: 'Веб-разработка', 
    slug: 'web-development', 
    item_count: 5, 
    is_visible: true,
    color: '#3B82F6',
    icon: 'pi pi-code'
  },
  { 
    id: 2, 
    name: 'UI/UX Дизайн', 
    slug: 'ui-ux-design', 
    item_count: 3, 
    is_visible: true,
    color: '#EC4899', 
    icon: 'pi pi-palette'
  },
  { 
    id: 3, 
    name: 'Базы данных', 
    slug: 'databases', 
    item_count: 2, 
    is_visible: false,
    color: '#10B981',
    icon: 'pi pi-database'
  }
])

// Методы
const editCategory = (category) => {
  editingCategory.value = category
  Object.assign(categoryForm, category)
  showCreateDialog.value = true
}

const deleteCategory = (category) => {
  if (confirm(`Удалить категорию "${category.name}"?`)) {
    categories.value = categories.value.filter(c => c.id !== category.id)
  }
}

const saveCategory = () => {
  if (editingCategory.value) {
    // Обновляем существующую категорию
    const index = categories.value.findIndex(c => c.id === editingCategory.value.id)
    categories.value[index] = { ...categoryForm, id: editingCategory.value.id }
  } else {
    // Создаем новую категорию
    const newCategory = {
      ...categoryForm,
      id: Date.now(),
      item_count: 0
    }
    categories.value.push(newCategory)
  }
  
  closeDialog()
}

const closeDialog = () => {
  showCreateDialog.value = false
  editingCategory.value = null
  // Сбрасываем форму
  Object.assign(categoryForm, {
    name: '',
    slug: '',
    description: '',
    color: '#3B82F6',
    icon: 'pi pi-folder',
    is_visible: true
  })
}
</script>

<style scoped>
.field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.field-checkbox {
  display: flex;
  align-items: center;
}
</style>