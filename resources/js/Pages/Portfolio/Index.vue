<template>
  <AppLayout title="Portfolio Saya">
    <div class="relative z-10">
      <BackButton href="/dashboard" label="Kembali ke Dashboard" />

      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
          <h1 class="text-4xl font-black uppercase tracking-tighter mb-2 text-white">Portfolio Saya</h1>
          <p class="text-white/40 text-xs font-bold uppercase tracking-widest">Kumpulan karya terbaik Anda untuk meyakinkan calon client</p>
        </div>
        <button 
          @click="showAddModal = true"
          class="bg-[#FF3366] text-white font-black px-10 py-4 rounded-lg hover:bg-[#e02d59] transition-all uppercase tracking-widest text-xs shadow-xl shadow-[#FF3366]/20"
        >
          + Tambah Item Baru
        </button>
      </div>

      <!-- Portfolio Grid -->
      <div v-if="portfolios.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="item in portfolios" 
          :key="item.id" 
          class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden group hover:border-white/20 transition-all duration-300 backdrop-blur-sm"
        >
          <div class="h-64 bg-white/5 relative overflow-hidden">
            <img :src="'/storage/' + item.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center p-8 text-center">
                <button @click="confirmDeleteItem(item)" class="bg-[#FF3366] text-white px-6 py-2 rounded-md text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-[#FF3366] transition-all mb-4">Hapus Item</button>
            </div>
          </div>
          <div class="p-8">
            <h3 class="text-xl font-black text-white uppercase tracking-tight mb-2 line-clamp-1">{{ item.title }}</h3>
            <p class="text-white/40 text-sm font-medium line-clamp-2 leading-relaxed">{{ item.description }}</p>
          </div>
        </div>

        <!-- Add New Placeholder -->
        <button 
          @click="showAddModal = true"
          class="bg-white/[0.02] border-2 border-dashed border-white/10 rounded-2xl flex flex-col items-center justify-center p-12 hover:bg-white/5 transition-all group min-h-[350px]"
        >
          <div class="w-16 h-16 rounded-md bg-white/5 flex items-center justify-center text-white/20 group-hover:bg-[#FF3366]/10 group-hover:text-[#FF3366] transition-all mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
          </div>
          <div class="text-[10px] font-black text-white/20 group-hover:text-white uppercase tracking-[0.4em]">Unggah Karya</div>
        </button>
      </div>

      <!-- Empty State -->
      <div v-else class="py-32 text-center bg-white/[0.02] rounded-[24px] border border-white/10 backdrop-blur-sm">
        <div class="w-24 h-24 bg-[#FF3366]/10 rounded-md flex items-center justify-center mx-auto mb-8">
          <svg class="w-12 h-12 text-[#FF3366]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h2 class="text-3xl font-black uppercase tracking-tighter mb-4 text-white">Portfolio Masih Kosong</h2>
        <p class="text-white/40 text-sm max-w-sm mx-auto mb-10 leading-relaxed font-medium uppercase tracking-tight">Karya-karya Anda akan ditampilkan di sini dan di profil publik Anda untuk menarik minat client.</p>
        <button @click="showAddModal = true" class="bg-[#FF3366] text-white font-black px-12 py-5 rounded-lg hover:bg-[#e02d59] transition-all uppercase tracking-widest text-xs shadow-2xl shadow-[#FF3366]/20">
          Upload Karya Pertama
        </button>
      </div>

      <!-- Add Modal -->
      <div v-if="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
        <div class="bg-[#0a0a0a] border border-white/10 p-10 rounded-[24px] w-full max-w-xl animate-slide-up relative overflow-hidden shadow-2xl">
          <div class="absolute top-0 right-0 w-32 h-32 bg-[#FF3366]/5 rounded-full blur-[40px]"></div>
          
          <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-black uppercase tracking-tighter text-white">Tambah Portfolio</h2>
            <button @click="showAddModal = false" class="text-white/20 hover:text-white transition-all p-2 hover:bg-white/5 rounded-md z-30">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>

          <form @submit.prevent="saveItem" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-white/30 uppercase tracking-widest mb-3">Judul Karya</label>
                        <input v-model="form.title" type="text" placeholder="Contoh: Logo Branding Project" class="w-full bg-white/5 border border-white/10 rounded-lg px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-white/30 uppercase tracking-widest mb-3">Deskripsi Singkat</label>
                        <textarea v-model="form.description" rows="4" placeholder="Jelaskan peran dan proses Anda..." class="w-full bg-white/5 border border-white/10 rounded-lg px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <label class="block text-[10px] font-bold text-white/30 uppercase tracking-widest">Gambar Karya</label>
                    <div 
                        @click="$refs.fileInput.click()"
                        class="aspect-square bg-white/5 border-2 border-dashed border-white/10 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-white/10 transition-all group relative overflow-hidden"
                    >
                        <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover">
                        <template v-else>
                            <svg class="w-10 h-10 text-white/10 group-hover:text-[#FF3366]/50 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div class="text-[8px] font-black text-white/20 mt-4 uppercase tracking-[0.3em]">Maks 2MB (JPG/PNG)</div>
                        </template>
                    </div>
                    <input type="file" ref="fileInput" hidden @change="handleFile" accept="image/*">
                </div>
            </div>

            <button 
                type="submit" 
                :disabled="loading || !form.image" 
                class="w-full bg-[#FF3366] text-white font-black py-5 rounded-lg hover:bg-[#e02d59] transition-all uppercase tracking-widest text-sm shadow-xl shadow-[#FF3366]/20 disabled:opacity-30"
            >
                {{ loading ? 'Mengunggah...' : 'Simpan Portfolio' }}
            </button>
          </form>
        </div>
      </div>
    </div>

    <ConfirmModal 
      :show="showDeleteModal"
      title="Hapus Portfolio"
      message="Apakah Anda yakin ingin menghapus karya ini? Tindakan ini tidak dapat dibatalkan."
      @confirm="executeDeleteItem"
      @close="showDeleteModal = false"
    />
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../Layouts/AppLayout.vue'
import BackButton from '../../Components/BackButton.vue'
import ConfirmModal from '../../Components/ConfirmModal.vue'
import { useApi } from '../../composables/useApi'
import { useToast } from '../../composables/useToast'

const { get, post, del } = useApi()
const { addToast } = useToast()

const portfolios = ref([])
const showAddModal = ref(false)
const loading = ref(false)
const previewUrl = ref(null)

const showDeleteModal = ref(false)
const itemToDelete = ref(null)

const form = ref({
    title: '',
    description: '',
    image: null
})

const fetchData = async () => {
    try {
        const res = await get('/portfolios')
        portfolios.value = res.data
    } catch (error) { console.error(error) }
}

const handleFile = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.value.image = file
        previewUrl.value = URL.createObjectURL(file)
    }
}

const saveItem = async () => {
    loading.value = true
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('description', form.value.description)
    formData.append('image', form.value.image)

    try {
        const res = await post('/portfolios', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        portfolios.value.unshift(res.data)
        showAddModal.value = false
        form.value = { title: '', description: '', image: null }
        previewUrl.value = null
        addToast('Karya berhasil ditambahkan ke portfolio!')
    } catch (error) {
        addToast('Gagal mengunggah portfolio', 'error')
    } finally {
        loading.value = false
    }
}

const confirmDeleteItem = (item) => {
    itemToDelete.value = item
    showDeleteModal.value = true
}

const executeDeleteItem = async () => {
    if (!itemToDelete.value) return
    try {
        await del(`/portfolios/${itemToDelete.value.id}`)
        portfolios.value = portfolios.value.filter(p => p.id !== itemToDelete.value.id)
        showDeleteModal.value = false
        itemToDelete.value = null
        addToast('Item portfolio berhasil dihapus')
    } catch (error) {
        addToast('Gagal menghapus item', 'error')
    }
}

onMounted(fetchData)
</script>
