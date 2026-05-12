<template>
  <AppLayout title="Edit Layanan">
    <div class="max-w-4xl mx-auto pb-20 relative z-10">
      
      <div class="mb-12">
        <Link :href="'/layanan'" class="text-white/40 hover:text-white text-[10px] font-black uppercase tracking-[0.3em] mb-6 inline-flex items-center gap-3 transition-all group">
          <div class="w-8 h-8 rounded-md bg-white/5 flex items-center justify-center group-hover:bg-[#FF3366]/10 group-hover:text-[#FF3366] transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          </div>
          Kembali ke Layanan
        </Link>
        <p class="text-white/40 text-sm font-medium mt-2 uppercase tracking-tight">Perbarui detail layananmu untuk menarik lebih banyak client.</p>
      </div>

      <!-- Error Summary -->
      <div v-if="Object.keys(form.errors).length > 0" class="mb-8 p-6 bg-[#FF3366]/10 border border-[#FF3366]/20 rounded-md animate-slide-up">
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
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#1A56FF]/5 rounded-full blur-[40px]"></div>
        
        <form @submit.prevent="submit" class="space-y-10">
          <!-- Title -->
          <div class="space-y-4">
            <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Judul Layanan</label>
            <input 
              v-model="form.title"
              type="text" 
              placeholder="Contoh: Desain Logo Minimalis untuk Brand" 
              class="w-full bg-white/5 border border-white/10 rounded-md p-5 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all text-lg font-black uppercase tracking-tight"
              :class="{'border-[#FF3366]/50': form.errors.title}"
            >
            <div v-if="form.errors.title" class="text-[#FF3366] text-[10px] font-black uppercase tracking-widest mt-2">{{ form.errors.title }}</div>
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
                      : (form.errors.category ? 'bg-white/5 border-[#FF3366]/30 text-white/40' : 'bg-white/5 border-white/10 text-white/40 hover:border-white/30 hover:bg-white/10')
                  ]"
                >
                  <span class="w-full px-1">{{ cat }}</span>
                </button>
              </div>
              <div v-if="form.errors.category" class="text-[#FF3366] text-[10px] font-black uppercase tracking-widest mt-2">{{ form.errors.category }}</div>
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
                  :class="{'border-[#FF3366]/50': form.errors.price}"
                >
              </div>
              <div v-if="form.errors.price" class="text-[#FF3366] text-[10px] font-black uppercase tracking-widest mt-2">{{ form.errors.price }}</div>
            </div>
          </div>

          <!-- Description -->
          <div class="space-y-4">
            <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Deskripsi Lengkap</label>
            <textarea 
              v-model="form.description"
              rows="8" 
              placeholder="Jelaskan apa yang akan didapatkan client, proses kerja, dan keunggulan jasamu..."
              class="w-full bg-white/5 border border-white/10 rounded-md p-6 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all resize-none leading-relaxed text-sm font-medium"
              :class="{'border-[#FF3366]/50': form.errors.description}"
            ></textarea>
            <div v-if="form.errors.description" class="text-[#FF3366] text-[10px] font-black uppercase tracking-widest mt-2">{{ form.errors.description }}</div>
          </div>

          <!-- Thumbnail -->
          <div class="space-y-4">
            <label class="block text-white/30 text-[10px] font-black uppercase tracking-[0.4em]">Thumbnail Visual</label>
            <div 
              class="border-2 border-dashed border-white/10 rounded-xl aspect-video flex flex-col items-center justify-center cursor-pointer hover:bg-white/[0.05] hover:border-[#FF3366]/50 transition-all overflow-hidden relative group"
              :class="{'border-[#FF3366]/30 bg-[#FF3366]/5': form.errors.thumbnail}"
              @click="$refs.fileInput.click()"
            >
              <img v-if="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover" />
              <div v-else class="text-center p-8">
                <div class="w-16 h-16 bg-white/5 rounded-md flex items-center justify-center mb-4 mx-auto group-hover:bg-[#FF3366]/10 group-hover:text-[#FF3366] transition-all">
                  <svg class="w-8 h-8 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">Klik untuk ganti thumbnail</p>
              </div>
              <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept="image/*" />
            </div>
            <div v-if="form.errors.thumbnail" class="text-[#FF3366] text-[10px] font-black uppercase tracking-widest mt-2">{{ form.errors.thumbnail }}</div>
          </div>

          <!-- Active Status -->
          <div class="pt-4">
            <label class="flex items-center gap-4 cursor-pointer group">
              <div class="relative">
                <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                <div class="w-10 h-6 bg-white/10 rounded-full peer-checked:bg-[#FF3366] transition-all"></div>
                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-4 transition-all"></div>
              </div>
              <span class="text-[10px] font-black uppercase tracking-widest text-white/40 group-hover:text-white transition-colors">Layanan Aktif</span>
            </label>
          </div>

          <!-- Submit -->
          <div class="flex items-center justify-end gap-8 border-t border-white/5 pt-12">
            <Link :href="'/layanan'" class="text-white/20 hover:text-white text-[10px] font-black uppercase tracking-[0.3em] transition-colors">Batal</Link>
            <button 
              type="submit"
              :disabled="form.processing"
              class="bg-[#FF3366] hover:bg-[#e02d59] text-white font-black px-12 py-5 rounded-md transition-all disabled:opacity-50 uppercase tracking-[0.2em] text-xs shadow-xl shadow-[#FF3366]/20 flex items-center gap-3"
            >
              <div v-if="form.processing" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
              {{ form.processing ? 'Sedang Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const props = defineProps({
  service: Object
});

const categories = ['Desain', 'Development', 'Fotografi', 'Video', 'Writing', 'Marketing'];
const previewUrl = ref(props.service.thumbnail);
const fileInput = ref(null);

const form = useForm({
  title: props.service.title,
  category: props.service.category,
  price: props.service.price,
  description: props.service.description,
  is_active: props.service.is_active === 1 || props.service.is_active === true,
  thumbnail: null,
  _method: 'PUT'
});

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.thumbnail = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

const submit = () => {
  form.post(`/layanan/${props.service.id}`);
};
</script>
