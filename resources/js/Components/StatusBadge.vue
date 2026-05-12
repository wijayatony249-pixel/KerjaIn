<template>
  <span :class="['text-[10px] uppercase px-3 py-1 tracking-widest font-black rounded-md border', statusClasses]">
    {{ status }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: String
})

const statusClasses = computed(() => {
  const s = props.status?.toLowerCase()
  switch (s) {
    case 'pending': return 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20'
    case 'accepted': return 'bg-[#1A56FF]/10 text-[#1A56FF] border-[#1A56FF]/20'
    case 'rejected': return 'bg-[#FF3366]/10 text-[#FF3366] border-[#FF3366]/20'
    case 'done': return 'bg-green-500/10 text-green-500 border-green-500/20'
    default: return 'bg-white/10 text-white/40 border-white/10'
  }
})
</script>
