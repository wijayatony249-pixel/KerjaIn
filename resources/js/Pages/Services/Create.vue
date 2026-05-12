<template>
  <AppLayout title="Tambah Layanan Baru">
    <div class="max-w-4xl mx-auto pb-20 relative z-10">
      
      <div class="mb-12">
        <Link href="/dashboard" class="text-white/40 hover:text-white text-[10px] font-black uppercase tracking-[0.3em] mb-6 inline-flex items-center gap-3 transition-all group">
          <div class="w-8 h-8 rounded-md bg-white/5 flex items-center justify-center group-hover:bg-[#FF3366]/10 group-hover:text-[#FF3366] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          </div>
          Kembali ke Dashboard
        </Link>
        <p class="text-white/40 text-sm font-medium mt-2 uppercase tracking-tight">Mulai tawarkan keahlianmu dan temukan client pertamamu.</p>
      </div>

      <!-- Error Summary -->
      <div v-if="Object.keys(form.errors).length > 0" class="mb-8 p-6 bg-[#FF3366]/10 border border-[#FF3366]/20 rounded-md">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-2 h-2 bg-[#FF3366] rounded-full animate-pulse"></div>
            <span class="text-xs font-black uppercase tracking-widest text-[#FF3366]">Mohon perbaiki kesalahan berikut:</span>
        </div>
        <ul class="space-y-1">
            <li v-for="(error, field) in form.errors" :key="field" class="text-[10px] text-white/60 font-bold uppercase tracking-tight">
                • {{ error }}
            </li>
        </ul>
      </div>

      <div class="bg-white/5 border border-white/10 rounded-xl p-10 md:p-14 backdrop-blur-xl shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#FF3366]/5 rounded-full blur-[40px]"></div>
        
        <form @submit.prevent="submit" class="space-y-10">
          <!-- Title -->
          <div class="space-y-4">
            <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Judul Layanan</label>
            <input 
              v-model="form.title"
              type="text" 
              placeholder="Contoh: Desain Logo Minimalis untuk Brand" 
              class="w-full bg-white/5 border border-white/10 rounded-md p-5 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all text-lg font-black uppercase tracking-tight placeholder:text-white/10"
              :class="{'border-[#FF3366]/50': form.errors.title}"
              required
            >
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Category -->
            <div class="space-y-4">
              <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Pilih Kategori</label>
              <div class="grid grid-cols-2 gap-3">
                <button 
                  v-for="cat in categories" 
                  :key="cat"
                  type="button"
                  @click="form.category = cat"
                  :class="[
                    'cursor-pointer px-3 py-4 rounded-md border text-center transition-all duration-300 text-[10px] font-black uppercase tracking-widest break-words flex items-center justify-center min-h-[60px]',
                    form.category === cat 
                      ? 'bg-[#FF3366] border-[#FF3366] text-white shadow-lg shadow-[#FF3366]/20' 
                      : 'bg-white/5 border-white/10 text-white/40 hover:border-white/30 hover:bg-white/10'
                  ]"
                >
                  <span class="w-full px-1">{{ cat }}</span>
                </button>
              </div>
            </div>

            <!-- Price -->
            <div class="space-y-4">
              <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Harga Layanan (Rp)</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-5 flex items-center text-[#FF3366] font-black text-xs">RP</span>
                <input 
                  v-model="form.price"
                  type="number" 
                  placeholder="500.000" 
                  class="w-full bg-white/5 border border-white/10 rounded-md py-5 pl-14 pr-5 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all font-black text-xl tracking-tighter"
                  required
                >
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="space-y-4">
            <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Deskripsi Lengkap</label>
            <textarea 
              v-model="form.description"
              rows="8" 
              placeholder="Jelaskan apa yang akan didapatkan client, proses kerja, dan keunggulan jasamu..."
              class="w-full bg-white/5 border border-white/10 rounded-md p-6 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all resize-none leading-relaxed text-sm font-medium placeholder:text-white/10"
              required
            ></textarea>
          </div>

          <!-- Thumbnail -->
          <div class="space-y-4">
            <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Thumbnail Visual</label>
            <div 
              class="border-2 border-dashed border-white/10 rounded-xl aspect-video flex flex-col items-center justify-center cursor-pointer hover:bg-white/[0.05] hover:border-[#FF3366]/50 transition-all overflow-hidden relative group"
              @click="$refs.fileInput.click()"
            >
              <template v-if="previewUrl">
                <img :src="previewUrl" class="absolute inset-0 w-full h-full object-cover" />
                <button @click.stop="removeThumbnail" class="absolute top-4 right-4 bg-red-500/80 text-white p-2 rounded-md hover:bg-red-600 transition-all z-20 backdrop-blur-md">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </template>
              <div v-else class="text-center p-8">
                <div class="w-16 h-16 bg-white/5 rounded-md flex items-center justify-center mb-4 mx-auto group-hover:bg-[#FF3366]/10 group-hover:text-[#FF3366] transition-all">
                  <svg class="w-8 h-8 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">Klik untuk unggah thumbnail</p>
              </div>
              <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept="image/*" />
            </div>
          </div>

          <!-- Submit -->
          <div class="flex items-center justify-end gap-8 border-t border-white/5 pt-12">
            <Link href="/dashboard" class="text-white/20 hover:text-white text-[10px] font-black uppercase tracking-[0.3em] transition-colors">Batal</Link>
            <button 
              type="submit"
              :disabled="form.processing"
              class="bg-[#FF3366] hover:bg-[#e02d59] text-white font-black px-12 py-5 rounded-md transition-all disabled:opacity-50 uppercase tracking-[0.2em] text-xs shadow-xl shadow-[#FF3366]/20 flex items-center gap-3"
            >
              <div v-if="form.processing" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
              {{ form.processing ? 'Sedang Mempublikasikan...' : 'Publikasikan Layanan Sekarang' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const categories = ['Desain Grafis', 'Programming', 'Fotografi', 'Videografi', 'Writing', 'Marketing'];
const previewUrl = ref(null);
const fileInput = ref(null);

const form = useForm({
  title: '',
  category: '',
  price: '',
  description: '',
  thumbnail: null
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.thumbnail = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const removeThumbnail = () => {
    form.thumbnail = null
    previewUrl.value = null
    if (fileInput.value) fileInput.value.value = ''
}

const submit = () => {
  if (!form.category) {
      form.errors.category = 'Silakan pilih kategori terlebih dahulu.';
      return;
  }
  
  form.post('/layanan', {
    preserveScroll: true,
    onSuccess: () => {
        setTimeout(() => {
            router.visit('/dashboard');
        }, 2000);
    },
    onError: (errors) => {
        // Errors are automatically handled by useForm and displayed in template
    }
  });
};
</script>
