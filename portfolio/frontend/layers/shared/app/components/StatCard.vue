<template>
  <Card :class="[colorClasses[color].card]">
    <template #content>
      <div class="flex justify-between items-center">
        <div>
          <div :class="['text-2xl font-bold', colorClasses[color].text]">{{ count }}</div>
          <div class="text-surface-600">{{ title }}</div>
        </div>
        <Icon :name="icon" size="32px" :class="[colorClasses[color].text]"></Icon>
      </div>
      <div class="mt-2 text-sm text-surface-500">
        {{ subtitle }}
      </div>
    </template>
  </Card>
</template>

<script setup lang="ts">
interface Props {
  title: string;
  count: number;
  subtitle: string;
  icon: string;
  color: 'rose' | 'lime' | 'violet' | 'amber'; // Магия: теперь редактор подскажет только эти 4 цвета!
}

defineProps<Props>();

const colorClasses = {
  rose: {
    card: 'bg-rose-50 border border-rose-300',
    text: 'text-rose-400'
  },
  lime: {
    card: 'bg-lime-50 border border-lime-300',
    text: 'text-lime-400'
  },
  violet: {
    card: 'bg-violet-50 border border-violet-300',
    text: 'text-violet-400'
  },
  amber: {
    card: 'bg-amber-50 border border-amber-300',
    text: 'text-amber-400'
  }
};
</script>