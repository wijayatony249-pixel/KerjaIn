<template>
  <AppLayout :title="service?.id ? 'Edit Layanan' : 'Tambah Layanan'">
    <div class="max-w-4xl mx-auto bg-white border border-black p-12">
      <h1 class="text-3xl font-black uppercase tracking-tighter mb-8">
        {{ service?.id ? 'Edit Layanan' : 'Buat Layanan Baru' }}
      </h1>

      <form @submit.prevent="handleSubmit" class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Left Col -->
          <div class="space-y-6">
            <div>
              <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Judul Layanan</label>
              <input
                v-model="form.title"
                type="text"
                placeholder="CONTOH: DESAIN LOGO MINIMALIS"
                class="w-full bg-white border border-black px-4 py-3 text-black text-sm focus:outline-none focus:border-[#1A56FF] transition-none"
                required
              />
              <p v-if="form.errors.title" class="text-[#E8192C] text-[10px] mt-1 uppercase font-bold">{{ form.errors.title }}</p>
            </div>

            <div>
              <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Kategori</label>
              <select
                v-model="form.category"
                class="w-full bg-white border border-black px-4 py-3 text-black text-sm focus:outline-none transition-none appearance-none cursor-pointer"
                required
              >
                <option value="">PILIH KATEGORI</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat.toUpperCase() }}</option>
              </select>
            </div>

            <div>
              <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Harga (Rp)</label>
              <input
                v-model="form.price"
                type="number"
                placeholder="500000"
                class="w-full bg-white border border-black px-4 py-3 text-black text-sm focus:outline-none focus:border-[#1A56FF] transition-none"
                required
              />
            </div>
          </div>

          <!-- Right Col -->
          <div class="space-y-6">
            <div>
              <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Thumbnail</label>
              <div 
                class="border-2 border-dashed border-black aspect-video flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 relative overflow-hidden"
                @click="$refs.fileInput.click()"
              >
                <img v-if="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover" />
                <template v-else>
                  <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Klik untuk upload</span>
                </template>
                <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept="image/*" />
              </div>
            </div>

            <div>
              <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Status</label>
              <div class="flex border border-black">
                <button
                  type="button"
                  @click="form.status = 'active'"
                  :class="[
                    'flex-1 py-3 text-[10px] font-black uppercase tracking-widest transition-none',
                    form.status === 'active' ? 'bg-[#1A56FF] text-white' : 'bg-white text-black'
                  ]"
                >
                  Aktif
                </button>
                <button
                  type="button"
                  @click="form.status = 'inactive'"
                  :class="[
                    'flex-1 py-3 text-[10px] font-black uppercase tracking-widest transition-none border-l border-black',
                    form.status === 'inactive' ? 'bg-black text-white' : 'bg-white text-black'
                  ]"
                >
                  Nonaktif
                </button>
              </div>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Deskripsi Layanan</label>
          <textarea
            v-model="form.description"
            rows="6"
            placeholder="JELASKAN APA YANG AKAN CLIENT DAPATKAN..."
            class="w-full bg-white border border-black px-4 py-3 text-black text-sm focus:outline-none focus:border-[#1A56FF] transition-none resize-none"
            required
          ></textarea>
        </div>

        <div class="pt-8 border-t border-black flex justify-end gap-4">
          <Link href="/layanan" class="px-8 py-4 border border-black text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-none">
            Batal
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-12 py-4 bg-[#1A56FF] text-white border border-black text-[10px] font-black uppercase tracking-widest hover:bg-black transition-none disabled:bg-gray-400"
          >
            {{ form.processing ? 'Memproses...' : 'Simpan Layanan' }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  service: {
    type: Object,
    default: null
  }
});

const categories = ['Desain', 'Fotografi', 'Pengembangan Web', 'Video', 'Penulisan', 'Lainnya'];
const previewUrl = ref(props.service?.thumbnail || null);

const form = useForm({
  title: props.service?.title || '',
  category: props.service?.category || '',
  description: props.service?.description || '',
  price: props.service?.price || '',
  status: props.service?.status || 'active',
  thumbnail: null,
});

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.thumbnail = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

const handleSubmit = () => {
  if (props.service?.id) {
    // Laravel PUT workaround for multipart/form-data
    form.post(`/api/services/${props.service.id}`, {
      _method: 'PUT',
      forceFormData: true,
      onSuccess: () => router.visit('/layanan')
    });
  } else {
    form.post('/api/services', {
      onSuccess: () => router.visit('/layanan')
    });
  }
};
</script>
