<template>
  <div class="mb-8">
    <Link :href="href" class="text-white/40 text-[10px] font-black uppercase tracking-[0.3em] hover:text-white flex items-center gap-3 transition-all group">
      <div class="w-8 h-8 rounded-md bg-white/5 flex items-center justify-center group-hover:bg-[#FF3366]/10 group-hover:text-[#FF3366] transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      </div>
      {{ label }}
    </Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href: {
    type: String,
    required: true
  },
  label: {
    type: String,
    default: 'Kembali'
  }
})
</script>
