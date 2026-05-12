<template>
  <div class="bg-white/5 border border-white/10 rounded-lg overflow-hidden group hover:border-white/20 transition-all duration-300 backdrop-blur-sm flex flex-col h-full">
    <!-- Top: Image Area -->
    <div class="h-48 bg-white/5 relative overflow-hidden">
      <img v-if="service.thumbnail" :src="'/storage/' + service.thumbnail" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500" />
      <div v-else class="flex items-center justify-center h-full text-white/10 text-xs font-bold uppercase tracking-widest">
        No Image
      </div>
      
      <div class="absolute top-4 left-4">
        <span class="bg-black/40 backdrop-blur-md text-white text-[10px] px-3 py-1 rounded-sm uppercase font-black border border-white/10">
          {{ service.category }}
        </span>
      </div>

      <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#FF3366] to-[#6C63FF] flex items-center justify-center text-[8px] font-black border border-white/20">
                {{ service.freelancer?.name?.[0] || '?' }}
            </div>
            <span class="text-white text-[10px] font-bold uppercase tracking-tight">{{ service.freelancer?.name }}</span>
        </div>
      </div>
    </div>

    <!-- Body -->
    <div class="p-6 flex-1 flex flex-col">
      <h3 class="font-bold text-lg mb-2 line-clamp-2 uppercase tracking-tight leading-tight text-white group-hover:text-[#FF3366] transition-colors">
        {{ service.title }}
      </h3>
      
      <div class="text-[#FF3366] font-black text-2xl mb-6 tracking-tighter mt-auto">
        Rp {{ formatPrice(service.price) }}
      </div>
      
      <!-- Action -->
      <Link 
        :href="`/layanan/${service.id}`" 
        class="w-full bg-white text-black text-center py-4 rounded-sm text-xs font-black uppercase tracking-widest hover:bg-[#FF3366] hover:text-white transition-all shadow-xl shadow-black/20"
      >
        Lihat Detail
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  service: Object
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price)
}
</script>
