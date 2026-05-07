<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white pt-24 pb-20" style="font-family: 'Inter', sans-serif;">
    <div class="max-w-3xl mx-auto px-8">
      
      <div class="mb-10">
        <Link :href="route('dashboard')" class="text-white/40 hover:text-white text-sm mb-4 inline-flex items-center gap-2 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Kembali ke Dashboard
        </Link>
        <h1 class="text-4xl font-black mt-4">Lengkapi Profil</h1>
        <p class="text-white/40">Atur profilmu agar client atau freelancer lain lebih mengenalmu.</p>
      </div>

      <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-8 md:p-12">
        <form @submit.prevent="submit">
          <!-- Avatar Section -->
          <div class="flex flex-col items-center mb-10 pb-10 border-b border-white/5">
            <div class="relative group cursor-pointer" @click="$refs.fileInput.click()">
              <div class="w-32 h-32 rounded-full bg-[#FF3366] flex items-center justify-center text-4xl font-black mb-4 overflow-hidden border-4 border-white/10">
                <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover" />
                <img v-else-if="user.avatar" :src="'/storage/' + user.avatar" class="w-full h-full object-cover" />
                <span v-else>{{ user.name.charAt(0) }}</span>
              </div>
              <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <!-- Hidden File Input -->
              <input 
                type="file" 
                ref="fileInput" 
                class="hidden" 
                accept="image/*"
                @change="handleFileChange"
              >
            </div>
            <p class="text-white/30 text-xs mt-2 uppercase font-bold tracking-widest">Klik untuk ganti foto dari komputer</p>
            <div v-if="form.errors.avatar" class="text-[#FF3366] text-xs mt-2">{{ form.errors.avatar }}</div>
          </div>

          <!-- Name -->
          <div class="mb-8">
            <label class="block text-white/50 text-xs font-bold uppercase tracking-wider mb-3">Nama Lengkap</label>
            <input 
              v-model="form.name"
              type="text" 
              class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all text-lg font-semibold"
              required
            >
            <div v-if="form.errors.name" class="text-[#FF3366] text-xs mt-1">{{ form.errors.name }}</div>
          </div>

          <!-- Bio -->
          <div class="mb-10">
            <label class="block text-white/50 text-xs font-bold uppercase tracking-wider mb-3">Tentang Saya (Bio)</label>
            <textarea 
              v-model="form.bio"
              rows="6" 
              placeholder="Ceritakan tentang keahlianmu, pengalaman, atau visi proyekmu..."
              class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white focus:outline-none focus:border-[#FF3366]/50 transition-all resize-none leading-relaxed"
            ></textarea>
            <div v-if="form.errors.bio" class="text-[#FF3366] text-xs mt-1">{{ form.errors.bio }}</div>
          </div>

          <!-- Submit -->
          <div class="flex items-center justify-end gap-4 border-t border-white/5 pt-10">
            <Link :href="route('dashboard')" class="text-white/40 hover:text-white font-bold transition-colors">Batal</Link>
            <button 
              type="submit"
              :disabled="form.processing"
              class="bg-[#FF3366] hover:bg-[#e02d59] text-white font-bold px-10 py-4 rounded-xl transition-all disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  user: Object
});

const previewUrl = ref(null);
const fileInput = ref(null);

const form = useForm({
  _method: 'PATCH', // Spoofing PATCH for file upload
  name: props.user.name,
  bio: props.user.bio || '',
  avatar: null
});

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.avatar = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

const submit = () => {
  // Use POST with _method spoofing for multipart/form-data
  form.post(route('profile.update'), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      // Success logic
    }
  });
};
</script>
