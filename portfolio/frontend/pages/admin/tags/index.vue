<template>
  <div class="p-4">
    <div 
      class="w-20 h-20 bg-purple-500 rounded-lg cursor-grab active:cursor-grabbing"
      :style="{ transform: `translateX(${position}px)` }"
      @pointerdown="startDrag"
      @pointermove="handleDrag"
      @pointerup="stopDrag"
      @pointercancel="stopDrag"
    ></div>
    <div class="mt-2">Перетащи меня! Позиция: {{ position }}px</div>
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
let rafId = null

const startDrag = (event) => {
  isDragging.value = true
  startX.value = event.clientX
  startPosition.value = position.value
  
  // ⚡ ДОБАВЛЯЕМ Pointer Capture
  event.currentTarget.setPointerCapture(event.pointerId)
}

const handleDrag = (event) => {
  if (!isDragging.value) return
  
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

// ⚡ ДОБАВЛЯЕМ очистку
onUnmounted(() => {
  if (rafId) {
    cancelAnimationFrame(rafId)
  }
})
</script>

<style scoped>
/* ⚡ ДОБАВЛЯЕМ критически важные стили */
div {
  will-change: transform;
  user-select: none;
  -webkit-user-select: none;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}
</style>