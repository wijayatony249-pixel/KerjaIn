<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-24 pb-20" style="font-family: 'Inter', sans-serif;">
    <div class="max-w-3xl mx-auto px-8">
      
      <div class="mb-10">
        <Link :href="route('dashboard')" class="text-white/40 hover:text-white text-sm mb-4 inline-flex items-center gap-2 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Kembali ke Dashboard
        </Link>
        <h1 class="text-4xl font-black mt-4">Tambah Layanan Baru</h1>
        <p class="text-white/40">Mulai tawarkan keahlianmu dan temukan client pertamamu.</p>
      </div>

      <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 md:p-12">
        <form @submit.prevent="submit">
          <!-- Title -->
          <div class="mb-8">
            <label class="block text-white/50 text-xs font-bold uppercase tracking-wider mb-3">Judul Layanan</label>
            <input 
              v-model="form.title"
              type="text" 
              placeholder="Contoh: Desain Logo Minimalis untuk Brand" 
              class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all text-lg font-semibold"
              required
            >
            <p class="text-white/20 text-xs mt-2">Buat judul yang menarik dan deskriptif.</p>
            <div v-if="form.errors.title" class="text-[#FF3366] text-xs mt-1">{{ form.errors.title }}</div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Category -->
            <div>
              <label class="block text-white/50 text-xs font-bold uppercase tracking-wider mb-3">Kategori</label>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div 
                  v-for="cat in categories" 
                  :key="cat"
                  @click="form.category = cat"
                  :class="[
                    'cursor-pointer p-3 rounded-xl border text-center transition-all duration-200 text-sm font-semibold',
                    form.category === cat 
                      ? 'bg-[#FF3366] border-[#FF3366] text-white shadow-[0_0_20px_rgba(255,51,102,0.3)]' 
                      : 'bg-white/5 border-white/10 text-white/50 hover:border-white/20 hover:bg-white/[0.08]'
                  ]"
                >
                  {{ cat }}
                </div>
              </div>
              <div v-if="form.errors.category" class="text-[#FF3366] text-xs mt-2">{{ form.errors.category }}</div>
            </div>

            <!-- Price -->
            <div>
              <label class="block text-white/50 text-xs font-bold uppercase tracking-wider mb-3">Harga (Rp)</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-white/30 font-bold">Rp</span>
                <input 
                  v-model="form.price"
                  type="number" 
                  placeholder="500.000" 
                  class="w-full bg-white/5 border border-white/10 rounded-xl py-4 pl-12 pr-4 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all"
                  required
                >
              </div>
              <div v-if="form.errors.price" class="text-[#FF3366] text-xs mt-1">{{ form.errors.price }}</div>
            </div>
          </div>

          <!-- Description -->
          <div class="mb-10">
            <label class="block text-white/50 text-xs font-bold uppercase tracking-wider mb-3">Deskripsi Lengkap</label>
            <textarea 
              v-model="form.description"
              rows="8" 
              placeholder="Jelaskan apa yang akan didapatkan client, proses kerja, dan keunggulan jasamu..."
              class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all resize-none leading-relaxed"
              required
            ></textarea>
            <div v-if="form.errors.description" class="text-[#FF3366] text-xs mt-1">{{ form.errors.description }}</div>
          </div>

          <!-- Submit -->
          <div class="flex items-center justify-end gap-4 border-t border-white/5 pt-10">
            <Link :href="route('dashboard')" class="text-white/40 hover:text-white font-bold transition-colors">Batal</Link>
            <button 
              type="submit"
              :disabled="form.processing"
              class="bg-[#FF3366] hover:bg-[#e02d59] text-white font-bold px-10 py-4 rounded-xl transition-all disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Publikasikan Layanan' }}
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const categories = ['Desain', 'Development', 'Fotografi', 'Video', 'Writing', 'Marketing'];

const form = useForm({
  title: '',
  category: '',
  price: '',
  description: '',
  thumbnail: null
});

const submit = () => {
  form.post(route('services.store'));
};
</script>
