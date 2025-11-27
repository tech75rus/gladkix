<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div
      class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-xl cursor-grab active:cursor-grabbing touch-none"
      :style="{ transform: `translateX(${position}px)` }"
      @pointerdown="startDrag"
      @pointermove="handleDrag"
      @pointerup="stopDrag"
      @pointercancel="stopDrag"
    >
      <div class="flex items-center justify-center h-full text-white font-bold text-lg">
        🎯
      </div>
    </div>
    
    <!-- Простой индикатор позиции -->
    <div class="fixed top-4 left-4 bg-black text-white px-3 py-2 rounded-lg text-sm">
      Позиция: {{ Math.round(position) }}px
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin'
})

const position = ref(0)
const isDragging = ref(false)
const startX = ref(0)
const startPosition = ref(0)

// ⚡ Добавляем requestAnimationFrame для максимальной плавности
let rafId = null

const startDrag = (event) => {
  isDragging.value = true
  startX.value = event.clientX
  startPosition.value = position.value
  event.currentTarget.setPointerCapture(event.pointerId)
}

const handleDrag = (event) => {
  if (!isDragging.value) return
  
  // ⚡ Используем requestAnimationFrame для плавности
  if (rafId) {
    cancelAnimationFrame(rafId)
  }
  
  rafId = requestAnimationFrame(() => {
    const deltaX = event.clientX - startX.value
    position.value = startPosition.value + deltaX
    rafId = null
  })
}

const stopDrag = () => {
  isDragging.value = false
  if (rafId) {
    cancelAnimationFrame(rafId)
    rafId = null
  }
}

// Очистка при размонтировании компонента
onUnmounted(() => {
  if (rafId) {
    cancelAnimationFrame(rafId)
  }
})
</script>

<style scoped>
div {
  will-change: transform;
  user-select: none;
  -webkit-user-select: none;
  backface-visibility: hidden;
}
</style>