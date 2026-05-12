<template>
  <div class="bg-white/5 border border-white/10 p-6 rounded-xl backdrop-blur-sm relative overflow-hidden group hover:bg-white/10 transition-all duration-300">
    <div :class="['absolute right-[-20px] top-[-20px] w-24 h-24 rounded-full blur-[40px] opacity-20', accentBlur]"></div>
    <div class="relative z-10">
      <div class="text-[10px] uppercase tracking-[0.2em] text-white/40 mb-2 font-bold">{{ label }}</div>
      <div class="text-4xl font-black text-white tracking-tighter">{{ value }}</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: String,
  value: [String, Number],
  accentColor: String
})

const accentBlur = computed(() => {
  switch (props.accentColor) {
    case 'blue': return 'bg-[#1A56FF]'
    case 'red': return 'bg-[#FF3366]'
    case 'black': return 'bg-white'
    case 'gray': return 'bg-gray-400'
    default: return 'bg-[#FF3366]'
  }
})
</script>
