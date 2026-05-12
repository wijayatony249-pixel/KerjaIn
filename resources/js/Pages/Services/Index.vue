<template>
  <AppLayout title="Jelajah Layanan">
    <div class="relative z-10">
      <!-- Header Section -->
      <div class="mb-12">
        <h1 class="text-6xl font-black uppercase tracking-tighter mb-4 text-white">
          Temukan <span class="text-[#FF3366]">Talenta</span><span class="text-white"> Terbaik</span>
        </h1>
        <p class="text-white/40 text-sm font-bold uppercase tracking-[0.2em] max-w-2xl leading-loose">
          Ribuan freelancer profesional siap membantu mewujudkan proyek impian Anda dengan kualitas tinggi dan pengerjaan cepat.
        </p>
      </div>

      <!-- Search & Filter Bar -->
      <div class="flex flex-col lg:flex-row gap-8 mb-16">
        <div class="flex-1 relative group">
          <div class="absolute inset-0 bg-[#FF3366]/5 rounded-md blur-xl group-focus-within:bg-[#FF3366]/10 transition-all"></div>
          <input 
            v-model="filters.search" 
            @input="handleSearch"
            type="text" 
            placeholder="Cari jasa (ex: Desain Logo, Web Dev...)" 
            class="relative w-full bg-white/5 border border-white/10 rounded-md px-8 py-5 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all pl-16 pr-14 backdrop-blur-xl"
          >
          <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-white/20 group-focus-within:text-[#FF3366] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <button v-if="filters.search" @click="clearSearch" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/20 hover:text-[#FF3366] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <div class="flex items-center gap-4 overflow-x-auto pb-6 lg:pb-0 no-scrollbar relative">
            <button 
                @click="filterByCategory('all')"
                :class="[
                    'px-8 py-4 rounded-md text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-500 border whitespace-nowrap backdrop-blur-md',
                    filters.category === 'all' ? 'bg-[#FF3366] text-white border-[#FF3366] shadow-[0_0_20px_rgba(255,51,102,0.4)] scale-105' : 'bg-white/5 text-white/40 border-white/10 hover:bg-white/10 hover:text-white hover:border-white/20'
                ]"
            >
                Semua
            </button>
            <button 
                v-for="cat in categories" 
                :key="cat"
                @click="filterByCategory(cat)"
                :class="[
                    'px-8 py-4 rounded-md text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-500 border whitespace-nowrap backdrop-blur-md',
                    filters.category === cat ? 'bg-[#FF3366] text-white border-[#FF3366] shadow-[0_0_20px_rgba(255,51,102,0.4)] scale-105' : 'bg-white/5 text-white/40 border-white/10 hover:bg-white/10 hover:text-white hover:border-white/20'
                ]"
            >
                {{ cat }}
            </button>
        </div>
      </div>

      <!-- Services Grid -->
      <div v-if="services.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <ServiceCard 
          v-for="service in services.data" 
          :key="service.id" 
          :service="service"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="py-40 text-center bg-white/[0.02] rounded-xl border border-white/5 backdrop-blur-sm">
        <div class="text-white/10 mb-8">
            <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <h2 class="text-2xl font-black uppercase tracking-tighter text-white mb-2">Tidak Ditemukan</h2>
        <p class="text-white/30 text-xs uppercase font-bold tracking-widest">Coba gunakan kata kunci lain atau kategori berbeda</p>
      </div>

      <!-- Pagination (Simple) -->
      <div v-if="services.links.length > 3" class="mt-20 flex justify-center gap-2">
        <template v-for="(link, k) in services.links" :key="k">
            <div 
                v-if="link.url === null" 
                class="px-4 py-2 text-white/20 text-xs font-black uppercase tracking-widest"
                v-html="link.label"
            />
            <Link 
                v-else 
                :href="link.url" 
                class="px-4 py-2 rounded-md text-xs font-black uppercase tracking-widest transition-all"
                :class="link.active ? 'bg-[#FF3366] text-white' : 'text-white/40 hover:text-white hover:bg-white/5'"
                v-html="link.label"
            />
        </template>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import ServiceCard from '../../Components/ServiceCard.vue'

const props = defineProps({
  services: Object,
  filters: Object
})

const categories = ['Desain Grafis', 'Programming', 'Fotografi', 'Videografi', 'Writing', 'Marketing']

const filters = reactive({
  search: props.filters.search || '',
  category: props.filters.category || 'all'
})

// Sync state if props change (e.g. back button)
watch(() => props.filters, (newFilters) => {
    filters.search = newFilters.search || ''
    filters.category = newFilters.category || 'all'
}, { deep: true })

let searchTimeout = null
const handleSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        router.get('/layanan', { 
            search: filters.search,
            category: filters.category !== 'all' ? filters.category : null
        }, { preserveState: true, preserveScroll: true, replace: true })
    }, 500)
}

const clearSearch = () => {
    filters.search = ''
    handleSearch()
}

const filterByCategory = (cat) => {
    filters.category = cat
    router.get('/layanan', { 
        search: filters.search,
        category: filters.category !== 'all' ? filters.category : null
    }, { preserveState: true, preserveScroll: true })
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  height: 4px;
}
.no-scrollbar::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.02);
  border-radius: 10px;
}
.no-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(255, 51, 102, 0.2);
  border-radius: 10px;
  transition: all 0.3s;
}
.no-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 51, 102, 0.5);
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 51, 102, 0.2) transparent;
  mask-image: linear-gradient(to right, black 85%, transparent 100%);
  -webkit-mask-image: linear-gradient(to right, black 85%, transparent 100%);
}
</style>
