<template>
  <AppLayout :title="isEdit ? 'Edit Layanan' : 'Tambah Layanan'">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
      <!-- Left: Form -->
      <div class="flex-1 w-full max-w-2xl">
        <div class="mb-8 flex items-center gap-4">
          <button @click="router.back()" class="bg-white border border-black p-2 hover:bg-black hover:text-white transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          </button>
          <div>
            <h1 class="text-3xl font-bold uppercase tracking-tighter">{{ isEdit ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h1>
            <p class="text-gray-500 text-xs uppercase tracking-widest mt-1">Lengkapi informasi jasa yang ingin Anda tawarkan</p>
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Basic Info -->
          <div class="border border-black p-6 bg-white space-y-4">
            <div class="flex items-center gap-2 mb-2">
              <div class="w-2 h-6 bg-[#1A56FF]"></div>
              <h2 class="font-bold uppercase tracking-widest text-sm">Informasi Layanan</h2>
            </div>

            <div>
              <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Judul Layanan</label>
              <input 
                v-model="form.title" 
                type="text" 
                placeholder="Contoh: Desain Logo Minimalis"
                class="w-full border border-black px-4 py-3 focus:outline-none focus:border-[#1A56FF] text-sm"
                required
              >
              <span v-if="errors.title" class="text-[#E8192C] text-[10px] font-bold uppercase mt-1">{{ errors.title }}</span>
            </div>

            <div>
              <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Kategori</label>
              <select 
                v-model="form.category" 
                class="w-full border border-black px-4 py-3 focus:outline-none focus:border-[#1A56FF] text-sm bg-white"
                required
              >
                <option value="">Pilih Kategori</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
              <span v-if="errors.category" class="text-[#E8192C] text-[10px] font-bold uppercase mt-1">{{ errors.category }}</span>
            </div>

            <div>
              <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Deskripsi</label>
              <textarea 
                v-model="form.description" 
                rows="6" 
                placeholder="Jelaskan detail layanan Anda..."
                class="w-full border border-black px-4 py-3 focus:outline-none focus:border-[#1A56FF] text-sm resize-none"
              ></textarea>
              <span v-if="errors.description" class="text-[#E8192C] text-[10px] font-bold uppercase mt-1">{{ errors.description }}</span>
            </div>

            <div>
              <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Harga (Rp)</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                <input 
                  v-model="form.price" 
                  type="number" 
                  placeholder="500000"
                  class="w-full border border-black pl-12 pr-4 py-3 focus:outline-none focus:border-[#1A56FF] text-sm"
                  required
                >
              </div>
              <div class="text-[10px] text-gray-500 mt-1 uppercase font-bold tracking-widest">
                Ditampilkan sebagai: <span class="text-black">Rp {{ formatPrice(form.price) }}</span>
              </div>
              <span v-if="errors.price" class="text-[#E8192C] text-[10px] font-bold uppercase mt-1">{{ errors.price }}</span>
            </div>
          </div>

          <!-- Thumbnail -->
          <div class="border border-black p-6 bg-white space-y-4">
            <div class="flex items-center gap-2 mb-2">
              <div class="w-2 h-6 bg-[#1A56FF]"></div>
              <h2 class="font-bold uppercase tracking-widest text-sm">Thumbnail Jasa</h2>
            </div>

            <div v-if="!previewUrl" class="relative group">
              <label for="thumbnail-input" class="cursor-pointer border-2 border-dashed border-black h-48 flex flex-col items-center justify-center hover:bg-gray-50 transition-all">
                <svg class="w-12 h-12 text-gray-200 group-hover:text-[#1A56FF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-2">Klik untuk upload thumbnail</div>
                <div class="text-[8px] text-gray-300 uppercase tracking-widest mt-1">JPG, PNG, MAX 2MB</div>
              </label>
              <input id="thumbnail-input" type="file" @change="handleFile" hidden accept="image/*">
            </div>

            <div v-else class="relative border border-black aspect-video overflow-hidden">
              <img :src="previewUrl" class="w-full h-full object-cover">
              <button @click="clearFile" class="absolute top-2 right-2 bg-white border border-black p-2 hover:bg-[#E8192C] hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <span v-if="errors.thumbnail" class="text-[#E8192C] text-[10px] font-bold uppercase mt-1">{{ errors.thumbnail }}</span>
          </div>

          <!-- Settings -->
          <div class="border border-black p-6 bg-white space-y-4">
            <div class="flex items-center gap-2 mb-2">
              <div class="w-2 h-6 bg-[#1A56FF]"></div>
              <h2 class="font-bold uppercase tracking-widest text-sm">Pengaturan</h2>
            </div>

            <div class="flex items-center justify-between">
              <div>
                <div class="text-[10px] font-bold uppercase tracking-widest">Status Layanan</div>
                <div class="text-[8px] text-gray-400 uppercase tracking-widest">Aktifkan agar layanan bisa ditemukan oleh client</div>
              </div>
              <div 
                @click="form.is_active = !form.is_active"
                class="w-12 h-6 border border-black relative cursor-pointer transition-all duration-200"
                :class="form.is_active ? 'bg-[#1A56FF]' : 'bg-gray-200'"
              >
                <div 
                  class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border border-black transition-all duration-200"
                  :class="form.is_active ? 'right-1' : 'left-1'"
                ></div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-4 pt-4 pb-12">
            <button 
              type="submit" 
              :disabled="loading"
              class="flex-1 bg-[#1A56FF] text-white font-bold py-4 border border-black hover:bg-white hover:text-[#1A56FF] transition-all uppercase tracking-widest text-sm disabled:opacity-50"
            >
              {{ loading ? 'Menyimpan...' : (isEdit ? 'Simpan Perubahan' : 'Terbitkan Layanan') }}
            </button>
            <button 
              type="button" 
              @click="router.back()"
              class="flex-1 bg-white text-black font-bold py-4 border border-black hover:bg-black hover:text-white transition-all uppercase tracking-widest text-sm"
            >
              Batal
            </button>
          </div>
        </form>
      </div>

      <!-- Right: Preview Panel -->
      <div class="hidden lg:block sticky top-24 w-80">
        <div class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          Live Preview Card
        </div>
        
        <div class="border border-black bg-white group flex flex-col h-full pointer-events-none">
          <div class="h-40 bg-gray-100 relative border-b border-black overflow-hidden">
            <img v-if="previewUrl" :src="previewUrl" class="object-cover w-full h-full grayscale">
            <div v-else class="flex items-center justify-center h-full text-gray-300 text-xs font-bold uppercase tracking-widest">
              No Image
            </div>
            <div class="absolute top-2 left-2">
              <span class="bg-[#1A56FF] text-white text-[10px] px-2 py-1 uppercase font-bold border border-black">
                {{ form.category || 'Kategori' }}
              </span>
            </div>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-sm mb-2 line-clamp-2 uppercase tracking-tight leading-tight">
              {{ form.title || 'Judul Layanan Anda' }}
            </h3>
            <div class="text-[#1A56FF] font-bold text-lg">
              Rp {{ formatPrice(form.price) }}
            </div>
          </div>
          <div class="border-t border-black bg-gray-50 p-4">
            <div class="bg-black text-white text-center py-2 text-[10px] font-bold uppercase tracking-widest">
              Pesan Sekarang
            </div>
          </div>
        </div>
        
        <div class="mt-4 text-[8px] text-gray-400 uppercase tracking-widest text-center">
          Begini tampilan layanan Anda di halaman publik
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import { useApi } from '../../composables/useApi'

const { post, get } = useApi()
const page = usePage()

const isEdit = computed(() => !!route().params.id)
const categories = ['Desain Grafis', 'Fotografi', 'Pengembangan Web', 'Editing Video', 'Penulisan Konten', 'Social Media', 'Lainnya']

const loading = ref(false)
const errors = ref({})
const previewUrl = ref(null)

const form = ref({
  title: '',
  category: '',
  description: '',
  price: 0,
  thumbnail: null,
  is_active: true
})

const handleFile = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.value.thumbnail = file
    previewUrl.value = URL.createObjectURL(file)
  }
}

const clearFile = () => {
  form.value.thumbnail = null
  previewUrl.value = null
  if (isEdit.value) {
      // In edit mode, we might want to keep the old one or set to null
  }
}

const fetchService = async () => {
  if (!isEdit.value) return
  try {
    const res = await get(`/services/${route().params.id}`)
    const data = res.data
    form.value = {
      title: data.title,
      category: data.category,
      description: data.description,
      price: data.price,
      is_active: !!data.is_active,
      thumbnail: null
    }
    if (data.thumbnail) {
      previewUrl.value = '/storage/' + data.thumbnail
    }
  } catch (error) {
    console.error('Failed to fetch service', error)
  }
}

const submit = async () => {
  loading.value = true
  errors.value = {}

  const formData = new FormData()
  Object.keys(form.value).forEach(key => {
    if (form.value[key] !== null) {
      if (key === 'is_active') {
        formData.append(key, form.value[key] ? 1 : 0)
      } else {
        formData.append(key, form.value[key])
      }
    }
  })

  try {
    const url = isEdit.value ? `/services/${route().params.id}?_method=PUT` : '/services'
    await post(url, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    router.visit('/layanan')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      alert('Terjadi kesalahan sistem')
    }
  } finally {
    loading.value = false
  }
}

const formatPrice = (price) => {
  if (!price) return '0'
  return new Intl.NumberFormat('id-ID').format(price)
}

// Mock route function if not available (since I removed Ziggy)
// Wait, I should use usePage().props to get params or something
const route = () => {
    const url = window.location.pathname
    const parts = url.split('/')
    // /layanan/1/edit
    const id = parts[2] !== 'buat' ? parts[2] : null
    return { params: { id } }
}

onMounted(fetchService)
</script>
