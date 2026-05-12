<template>
  <div class="bg-white/5 border border-white/10 rounded-lg overflow-hidden group hover:border-white/20 transition-all duration-300 backdrop-blur-sm">
    <!-- Top: Image Area -->
    <div class="h-48 bg-white/5 relative overflow-hidden">
      <img v-if="service.thumbnail" :src="'/storage/' + service.thumbnail" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500" />
      <div v-else class="flex items-center justify-center h-full text-white/10 text-xs font-bold uppercase tracking-widest">
        No Image
      </div>
      
      <div class="absolute top-4 left-4">
        <span class="bg-black/40 backdrop-blur-md text-white text-[10px] px-3 py-1 rounded-md uppercase font-black border border-white/10">
          {{ service.category }}
        </span>
      </div>

      <div class="absolute top-4 right-4">
        <button 
          @click="$emit('toggle-status')"
          :class="[
            'text-[10px] px-3 py-1 rounded-sm uppercase font-black border transition-all',
            service.is_active ? 'bg-green-500/20 text-green-500 border-green-500/20' : 'bg-white/10 text-white/40 border-white/10'
          ]"
        >
          {{ service.is_active ? 'Aktif' : 'Nonaktif' }}
        </button>
      </div>
    </div>

    <!-- Body -->
    <div class="p-6">
      <h3 class="font-bold text-lg mb-2 line-clamp-1 uppercase tracking-tight leading-tight text-white group-hover:text-[#FF3366] transition-colors">
        {{ service.title }}
      </h3>
      <div class="text-[#FF3366] font-black text-2xl mb-4 tracking-tighter">
        Rp {{ formatPrice(service.price) }}
      </div>
      
      <!-- Footer Actions -->
      <div class="flex gap-3 pt-4 border-t border-white/5">
        <Link :href="`/layanan/${service.id}/edit`" class="flex-1 bg-white/5 text-center py-3 rounded-sm text-xs font-black hover:bg-white/10 text-white uppercase tracking-widest transition-all">
          Edit
        </Link>
        <button @click="$emit('delete')" class="flex-1 bg-[#FF3366]/10 text-center py-3 rounded-sm text-xs font-black text-[#FF3366] hover:bg-[#FF3366]/20 uppercase tracking-widest transition-all">
          Hapus
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  service: Object
})

defineEmits(['delete', 'toggle-status'])

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}
</script>
