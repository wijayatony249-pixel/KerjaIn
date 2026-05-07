<template>
  <AppLayout title="Jelajahi Layanan">
    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12 relative z-10">
      <div class="flex-1 w-full max-w-xl relative group">
        <input 
          v-model="search" 
          type="text" 
          placeholder="CARI JASA KREATIF..." 
          class="w-full bg-white/5 border border-white/10 px-8 py-5 rounded-lg text-white text-xs font-bold uppercase tracking-[0.2em] focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
          @keyup.enter="handleSearch"
        />
        <button @click="handleSearch" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/40 hover:text-[#FF3366] transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
      </div>

      <div class="flex items-center gap-4 w-full md:w-auto">
        <div class="relative flex-1 md:flex-none">
          <select 
            v-model="selectedCategory" 
            @change="handleSearch"
            class="w-full bg-white/5 border border-white/10 px-8 py-5 rounded-lg text-[10px] font-black uppercase tracking-[0.2em] text-white focus:outline-none focus:border-[#FF3366]/50 transition-all appearance-none cursor-pointer"
          >
            <option value="" class="bg-[#0a0a0a]">SEMUA KATEGORI</option>
            <option v-for="cat in categories" :key="cat" :value="cat" class="bg-[#0a0a0a] text-white">{{ cat.toUpperCase() }}</option>
          </select>
          <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-white/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </div>
        </div>

        <Link 
          v-if="user?.role === 'freelancer'"
          href="/layanan/buat" 
          class="bg-[#FF3366] text-white px-8 py-5 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#e02d59] shadow-lg shadow-[#FF3366]/20 transition-all text-center flex-1 md:flex-none"
        >
          + Jual Jasa
        </Link>
      </div>
    </div>

    <!-- Grid -->
    <div v-if="servicesList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 relative z-10">
      <div 
        v-for="service in servicesList" 
        :key="service.id"
        class="bg-white/[0.03] border border-white/5 rounded-xl overflow-hidden group hover:border-[#FF3366]/40 transition-all duration-500 hover:-translate-y-2 shadow-xl shadow-black/20"
      >
        <!-- Thumbnail -->
        <div class="aspect-[4/3] bg-white/5 relative overflow-hidden">
          <div class="absolute inset-0 flex items-center justify-center text-white/5 font-black uppercase tracking-tighter text-4xl">
            KERJA//IN
          </div>
          <img 
            v-if="service.thumbnail" 
            :src="service.thumbnail" 
            class="w-full h-full object-cover relative z-10 transition-transform duration-700 group-hover:scale-110" 
          />
          <div class="absolute top-4 left-4 z-20">
            <span class="px-3 py-1 bg-black/50 backdrop-blur-md border border-white/10 rounded-full text-[8px] font-black text-white uppercase tracking-widest">
              {{ service.category }}
            </span>
          </div>
        </div>
        
        <div class="p-8">
          <Link :href="`/layanan/${service.id}`" class="block text-xl font-black uppercase tracking-tighter mb-4 group-hover:text-[#FF3366] transition-colors line-clamp-2 min-h-[3.5rem]">
            {{ service.title }}
          </Link>

          <div class="flex items-center gap-3 mb-8">
            <div class="w-8 h-8 rounded-md bg-gradient-to-br from-[#FF3366] to-[#6C63FF] flex items-center justify-center text-white text-[10px] font-black uppercase">
              {{ service.freelancer?.name?.[0] || '?' }}
            </div>
            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">{{ service.freelancer?.name }}</span>
          </div>

          <div class="flex items-center justify-between pt-6 border-t border-white/5">
            <div>
              <div class="text-white/20 text-[8px] font-black uppercase tracking-widest mb-1">MULAI DARI</div>
              <div class="text-xl font-black text-white">Rp{{ formatNumber(service.price) }}</div>
            </div>
            
            <div class="flex gap-2">
              <template v-if="service.freelancer_id === user?.id">
                <Link :href="`/layanan/${service.id}/edit`" class="w-10 h-10 flex items-center justify-center bg-white/5 border border-white/10 rounded-md hover:bg-[#FF3366] hover:text-white transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </Link>
              </template>
              <template v-else>
                <Link :href="`/layanan/${service.id}`" class="px-6 py-3 bg-white text-black rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#FF3366] hover:text-white transition-all shadow-lg">
                  Detail
                </Link>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="py-32 bg-white/[0.02] border-2 border-dashed border-white/5 rounded-3xl flex flex-col items-center justify-center text-center relative z-10">
      <div class="text-6xl text-white/5 font-black mb-6 uppercase tracking-tighter animate-pulse">KERJA//IN</div>
      <h3 class="text-2xl font-black uppercase tracking-tighter mb-2">Layanan tidak ditemukan</h3>
      <p class="text-white/20 text-[10px] font-black uppercase tracking-widest">Coba gunakan kata kunci atau kategori yang berbeda.</p>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import { useAuth } from '../../composables/useAuth';

const props = defineProps({
  services: Object,
  filters: Object
});

const { user } = useAuth();
const search = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category || '');
const servicesList = ref(props.services?.data || []);

const categories = ['Desain', 'Fotografi', 'Web Dev', 'Video', 'Penulisan', 'Musik'];

const handleSearch = () => {
  router.get('/layanan', { 
    search: search.value,
    category: selectedCategory.value 
  }, { preserveState: true });
};

const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num || 0);
};
</script>
