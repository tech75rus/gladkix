<template>
  <Card class="editor-card">
    
    <template #title>
      <div class="flex align-items-center">
        <i class="pi pi-file-edit mr-2"></i>
        <span>Простой редактор</span>
      </div>
    </template>

    <template #content>
      
      <Toolbar class="mb-3">
        <template #start>          
          <Button 
            @click="editor.chain().focus().toggleBold().run()"
            :class="{ 
              'p-button-outlined': !editor?.isActive('bold'), 
              'p-button-primary': editor?.isActive('bold') 
            }"
            icon="pi pi-bold"
            v-tooltip="'Сделать текст жирным'"
          />
          
          <!-- Кнопка для курсива -->
          <Button 
            @click="editor.chain().focus().toggleItalic().run()"
            :class="{ 
              'p-button-outlined': !editor?.isActive('italic'), 
              'p-button-primary': editor?.isActive('italic') 
            }"
            icon="pi pi-italic"
            v-tooltip="'Сделать текст курсивом'"
            />
          <Button 
            @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
            :class="{ 
              'p-button-outlined': !editor?.isActive('heading', { level: 2 }), 
              'p-button-primary': editor?.isActive('heading', { level: 2 }) 
            }"
            label="H2"
            v-tooltip="'Сделать заголовок 2 уровня'"
          >
            <template #icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.5 13.5v-11h1v4.75h6.5v-4.75h1v11h-1v-5.25h-6.5v5.25h-1z"/>
                <path d="M13.734 10.065v-.805h-2.634v-.566h2.447v-.805h-2.447v-.566h2.634v-.805h-3.444v3.547h3.444z"/>
              </svg>
            </template>
          </Button>
        </template>


        <template #end>
          <Button 
            @click="editor.chain().focus().clearNodes().run()"
            icon="pi pi-times"
            label="Очистить"
            class="p-button-secondary"
            v-tooltip="'Очистить форматирование'"
          />
        </template>
      </Toolbar>

      <div class="editor-container">
        <editor-content :editor="editor" class="editor-content" />
      </div>

    </template>

    <!-- #footer - слот для нижней части карточки -->
    <template #footer>
      <!-- Группа кнопок в футере -->
      <div class="flex gap-2">
        <!-- Кнопка предпросмотра -->
        <Button 
          @click="showPreview"
          icon="pi pi-eye"
          label="Предпросмотр"
          class="p-button-help"
        />
        <!-- Кнопка сохранения -->
        <Button 
          @click="saveContent"
          icon="pi pi-save" 
          label="Сохранить"
          class="p-button-success"
        />
      </div>
    </template>
  </Card>
</template>

// script setup - синтаксис Composition API Vue 3 
<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

// Импорты из Tiptap
// useEditor - хук для создания экземпляра редактора
// EditorContent - компонент для отображения области редактирования
import { useEditor, EditorContent } from '@tiptap/vue-3'
// StarterKit - основной набор расширений Tiptap
import StarterKit from '@tiptap/starter-kit'

const htmlContent = ref('')

// Получаем экземпляр toast для показа уведомлений
const toast = useToast()

// Инициализация редактора Tiptap
// useEditor() - создает и настраивает экземпляр редактора
const editor = useEditor({
  // content - начальное содержимое редактора в формате HTML
  content: `
    <h2>Добро пожаловать в редактор!</h2>
    <p>Это <strong>простой пример</strong> интеграции <em>Tiptap</em> с <strong>PrimeVue</strong>.</p>
    <p>Попробуйте выделить текст и нажать кнопки форматирования.</p>
  `,
  
  // extensions - подключаемые расширения (функциональность)
  extensions: [
    // StarterKit - основной набор: абзацы, заголовки, жирный, курсив, списки и т.д.
    StarterKit,
  ],

  // onUpdate - функция, вызываемая при любом изменении содержимого
  onUpdate: ({ editor }) => {
    // editor.getHTML() - получает текущее содержимое в формате HTML
    // htmlContent.value - сохраняем HTML в нашу реактивную переменную
    htmlContent.value = editor.getHTML()
    
    // Выводим в консоль для отладки
    console.log('Контент обновлен:', htmlContent.value)
  },

  // onFocus - функция, вызываемая когда редактор получает фокус
  onFocus: () => {
    console.log('Редактор получил фокус')
  },

  // onBlur - функция, вызываемая когда редактор теряет фокус  
  onBlur: () => {
    console.log('Редактор потерял фокус')
  },
})

// Функция для показа предпросмотра
const showPreview = () => {
  // Проверяем что редактор инициализирован
  if (editor.value) {
    // Показываем уведомление с информацией
    toast.add({
      severity: 'info',           // Тип уведомления (info, success, error, warn)
      summary: 'Предпросмотр',    // Заголовок уведомления
      detail: 'Проверьте консоль браузера', // Текст уведомления
      life: 3000                  // Время показа в миллисекундах
    })
    
    // Выводим в консоль оба формата содержимого
    console.log('HTML содержимое:', editor.value.getHTML())
    console.log('JSON содержимое:', editor.value.getJSON())
  }
}

// Функция для сохранения содержимого
const saveContent = () => {
  // Проверяем что редактор инициализирован
  if (editor.value) {
    // Получаем содержимое в двух форматах
    const html = editor.value.getHTML()
    const json = editor.value.getJSON()
    
    // Показываем уведомление об успешном сохранении
    toast.add({
      severity: 'success',
      summary: 'Сохранено!',
      detail: 'Контент готов для сохранения в БД',
      life: 3000
    })
    
    // Выводим в консоль для демонстрации
    console.log('=== ДАННЫЕ ДЛЯ СОХРАНЕНИЯ В БАЗУ ===')
    console.log('HTML (для отображения):', html)
    console.log('JSON (для редактора):', json)
    
    // Здесь будет запрос к API для сохранения в базу данных
    // Например: await api.saveArticle({ html, json })
  }
}

// Хук жизненного цикла - выполняется после монтирования компонента
onMounted(() => {
  console.log('Компонент редактора загружен')
  
  // Сохраняем начальное содержимое
  if (editor.value) {
    htmlContent.value = editor.value.getHTML()
  }
})

// Хук жизненного цикла - выполняется перед удалением компонента
onBeforeUnmount(() => {
  console.log('Компонент редактора удаляется')
  
  // Важно: уничтожаем редактор чтобы избежать утечек памяти
  if (editor.value) {
    editor.value.destroy()
  }
})
</script>

<style scoped>
.editor-card {
  max-width: 800px;
  margin: 20px auto;
}

.editor-container {
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  overflow: hidden;
}

.editor-content {
  padding: 20px;
  min-height: 300px;
  background: white;
}

:deep(.ProseMirror) {
  outline: none;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.6;
  font-size: 16px;
}

:deep(.ProseMirror h2) {
  color: #1f2937;
  margin: 1.5em 0 0.5em 0;
  font-size: 1.5em;
  font-weight: 600;
}

:deep(.ProseMirror p) {
  margin-bottom: 1em;
}

:deep(.ProseMirror:focus) {
  outline: none;
}

:deep(.ProseMirror .selection) {
  background: #3b82f6;
  color: white;
}
</style>