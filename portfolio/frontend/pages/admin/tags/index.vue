<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-surface-900">Теги</h1>
        <p class="text-surface-600">Управление тегами для статей и проектов</p>
      </div>
      <Button 
        label="Новый тег" 
        icon="pi pi-plus" 
        @click="showCreateDialog = true"
      />
    </div>

    <Card>
      <template #content>
        <DataTable :value="tags" paginator :rows="10">
          <Column field="id" header="ID" style="width: 80px"></Column>
          <Column field="name" header="Название тега"></Column>
          <Column field="description" header="Описание"></Column>
          <Column header="Действия" style="width: 120px">
            <template #body="slotProps">
              <div class="flex gap-2">
                <Button 
                  icon="pi pi-pencil" 
                  text rounded 
                  severity="warning"
                  @click="editTag(slotProps.data)"
                />
                <Button 
                  icon="pi pi-trash" 
                  text rounded categories
                  severity="danger"
                  @click="deleteTag(slotProps.data)"
                />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <!-- Диалог тега -->
    <Dialog 
      v-model:visible="showCreateDialog" 
      modal 
      :header="editingTag ? 'Редактировать тег' : 'Новый тег'"
      :style="{ width: '500px' }"
    >
      <div class="flex flex-col gap-4">
        <div class="field">
          <label for="tagName" class="font-medium">Название тега *</label>
          <InputText 
            id="tagName" 
            v-model="tagForm.name" 
            class="w-full" 
            placeholder="Vue.js"
          />
        </div>

        <div class="field">
          <label for="tagDescription" class="font-medium">Описание</label>
          <Textarea 
            id="tagDescription" 
            v-model="tagForm.description" 
            class="w-full" 
            rows="3"
            placeholder="Описание тега..."
          />
        </div>
      </div>

      <template #footer>
        <Button label="Отмена" severity="secondary" @click="closeDialog" />
        <Button :label="editingTag ? 'Обновить' : 'Создать'" @click="saveTag" />
      </template>
    </Dialog>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})

const showCreateDialog = ref(false)
const editingTag = ref(null)

const tagForm = reactive({
  name: '',
  description: ''
})

const tags = ref([
  { id: 1, name: 'Vue.js', description: 'Фреймворк для построения пользовательских интерфейсов' },
  { id: 2, name: 'Nuxt', description: 'Фреймворк для Vue.js с SSR' },
  { id: 3, name: 'JavaScript', description: 'Язык программирования' },
  { id: 4, name: 'TypeScript', description: 'Надмножество JavaScript с типами' },
  { id: 5, name: 'Symfony', description: 'PHP фреймворк' }
])

const editTag = (tag) => {
  editingTag.value = tag
  Object.assign(tagForm, tag)
  showCreateDialog.value = true
}

const deleteTag = (tag) => {
  if (confirm(`Удалить тег "${tag.name}"?`)) {
    tags.value = tags.value.filter(t => t.id !== tag.id)
  }
}

const saveTag = () => {
  if (editingTag.value) {
    const index = tags.value.findIndex(t => t.id === editingTag.value.id)
    tags.value[index] = { ...tagForm, id: editingTag.value.id }
  } else {
    const newTag = {
      ...tagForm,
      id: Date.now()
    }
    tags.value.push(newTag)
  }
  
  closeDialog()
}

const closeDialog = () => {
  showCreateDialog.value = false
  editingTag.value = null
  Object.assign(tagForm, {
    name: '',
    description: ''
  })
}
</script>