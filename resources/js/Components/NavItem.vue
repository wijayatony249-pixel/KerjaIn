<template>
  <Link
    :href="href"
    :class="[
      'h-12 flex items-center gap-4 px-4 rounded-xl text-xs font-bold uppercase tracking-widest transition-all duration-200 group',
      active 
        ? 'bg-[#FF3366] text-white shadow-lg shadow-[#FF3366]/20' 
        : 'text-white/40 hover:text-white hover:bg-white/5'
    ]"
  >
    <component :is="icon" class="w-5 h-5 transition-transform group-hover:scale-110" />
    {{ label }}
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href: String,
  label: String,
  icon: Object,
  active: Boolean
})
</script>
