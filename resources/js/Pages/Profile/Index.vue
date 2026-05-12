<template>
  <AppLayout title="Profil Saya">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left: Profile Card -->
      <div class="lg:col-span-1 space-y-6">
        <div class="border border-black p-8 bg-white text-center flex flex-col items-center">
          <div class="w-24 h-24 bg-[#1A56FF] text-white text-4xl font-bold flex items-center justify-center border border-black mb-6">
            <img v-if="user.avatar_url" :src="user.avatar_url" class="w-full h-full object-cover">
            <span v-else>{{ user.name?.[0] }}</span>
          </div>
          
          <h2 class="text-2xl font-bold uppercase tracking-tighter mb-1">{{ user.name }}</h2>
          <div :class="['text-[10px] font-bold uppercase tracking-widest px-3 py-1 border border-black mb-4 inline-block', user.role === 'freelancer' ? 'bg-[#1A56FF] text-white' : 'bg-black text-white']">
            {{ user.role }}
          </div>
          <p class="text-gray-400 text-sm mb-6">{{ user.email }}</p>
          
          <div class="w-full border-t border-black my-6"></div>
          
          <div class="w-full text-left space-y-4">
            <div class="flex gap-4 items-start">
              <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1a11 11 0 01-11-11V5z"/></svg>
              <div class="text-sm font-medium">{{ user.phone || 'Belum diisi' }}</div>
            </div>
            <div class="flex gap-4 items-start">
              <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <div class="text-sm font-medium italic text-gray-500">{{ user.bio || 'Belum ada bio' }}</div>
            </div>
          </div>

          <div v-if="isFreelancer" class="grid grid-cols-3 border border-black w-full mt-8 overflow-hidden">
            <div class="p-4 border-r border-black">
                <div class="text-xl font-bold text-[#1A56FF]">{{ stats.services }}</div>
                <div class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Layanan</div>
            </div>
            <div class="p-4 border-r border-black">
                <div class="text-xl font-bold text-[#1A56FF]">{{ stats.bookings }}</div>
                <div class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Booking</div>
            </div>
            <div class="p-4">
                <div class="text-xl font-bold text-[#1A56FF]">{{ stats.rating }}</div>
                <div class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Rating</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Form & Portfolio -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Edit Profile Form -->
        <div class="border border-black p-8 bg-white">
          <h2 class="text-2xl font-bold uppercase tracking-tighter mb-8">Edit Profil</h2>
          
          <div class="flex items-center gap-8 mb-8 pb-8 border-b border-black">
            <div class="w-16 h-16 bg-[#1A56FF] text-white flex items-center justify-center border border-black shrink-0 overflow-hidden">
                <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover">
                <img v-else-if="user.avatar_url" :src="user.avatar_url" class="w-full h-full object-cover">
                <span v-else>{{ user.name?.[0] }}</span>
            </div>
            <div class="flex-1">
                <div class="text-sm font-bold uppercase tracking-tight mb-1">Foto Profil</div>
                <div class="text-[10px] text-gray-400 uppercase tracking-widest mb-3">JPG, PNG MAX 1MB</div>
                <div class="flex gap-4">
                    <button @click="$refs.avatarInput.click()" class="bg-white text-black text-[10px] font-bold px-4 py-2 border border-black hover:bg-black hover:text-white transition-all uppercase tracking-widest">Upload Foto</button>
                    <button v-if="avatarPreview" @click="clearAvatar" class="text-[#E8192C] text-[10px] font-bold uppercase tracking-widest hover:underline">Hapus</button>
                </div>
                <input type="file" ref="avatarInput" hidden @change="handleAvatar" accept="image/*">
            </div>
          </div>

          <form @submit.prevent="updateProfile" class="space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Nama Lengkap</label>
                    <input v-model="form.name" type="text" class="w-full border border-black px-4 py-3 focus:outline-none focus:border-[#1A56FF] text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Email</label>
                    <input :value="user.email" type="text" disabled class="w-full border border-black px-4 py-3 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">No. Telepon</label>
                    <input v-model="form.phone" type="text" class="w-full border border-black px-4 py-3 focus:outline-none focus:border-[#1A56FF] text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Role</label>
                    <input :value="user.role" type="text" disabled class="w-full border border-black px-4 py-3 text-sm bg-gray-50 text-gray-400 uppercase font-bold tracking-widest">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Bio</label>
                    <textarea v-model="form.bio" rows="4" class="w-full border border-black px-4 py-3 focus:outline-none focus:border-[#1A56FF] text-sm resize-none"></textarea>
                </div>
            </div>

            <button type="submit" :disabled="loading" class="bg-[#1A56FF] text-white font-bold px-12 py-4 border border-black hover:bg-white hover:text-[#1A56FF] transition-all uppercase tracking-widest text-sm disabled:opacity-50">
              {{ loading ? 'Menyimpan...' : (success ? '✓ Tersimpan' : 'Simpan Perubahan') }}
            </button>
          </form>
        </div>

        <!-- Portfolio Section (Freelancer Only) -->
        <div v-if="isFreelancer" class="border border-black p-8 bg-white">
          <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold uppercase tracking-tighter">Portfolio Saya</h2>
            <button @click="showAddPortfolio = !showAddPortfolio" class="bg-[#1A56FF] text-white font-bold px-6 py-2 border border-black hover:bg-white hover:text-[#1A56FF] transition-all uppercase tracking-widest text-[10px]">
              {{ showAddPortfolio ? 'Tutup' : '+ Tambah' }}
            </button>
          </div>

          <!-- Add Portfolio Form -->
          <div v-if="showAddPortfolio" class="mb-12 border-2 border-dashed border-black p-6 bg-gray-50 animate-slide-up">
            <h3 class="font-bold uppercase tracking-widest text-xs mb-6">Tambah Portfolio Baru</h3>
            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <div class="w-full md:w-48 h-32 border border-black bg-white flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 group" @click="$refs.portfolioInput.click()">
                    <img v-if="portfolioPreview" :src="portfolioPreview" class="w-full h-full object-cover">
                    <template v-else>
                        <svg class="w-8 h-8 text-gray-200 group-hover:text-[#1A56FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div class="text-[8px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Upload Gambar</div>
                    </template>
                    <input type="file" ref="portfolioInput" hidden @change="handlePortfolioFile" accept="image/*">
                </div>
                <div class="flex-1 space-y-4">
                    <div>
                        <input v-model="portfolioForm.title" type="text" placeholder="Judul Portfolio" class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:border-[#1A56FF]">
                    </div>
                    <div>
                        <textarea v-model="portfolioForm.description" rows="2" placeholder="Deskripsi singkat..." class="w-full border border-black px-4 py-3 text-sm focus:outline-none focus:border-[#1A56FF] resize-none"></textarea>
                    </div>
                </div>
            </div>
            <button @click="savePortfolio" :disabled="!portfolioForm.title || !portfolioForm.image" class="w-full bg-black text-white font-bold py-3 border border-black hover:bg-[#1A56FF] transition-all uppercase tracking-widest text-xs disabled:opacity-30">
              Simpan Portfolio
            </button>
          </div>

          <!-- Portfolios Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in portfolios" :key="item.id" class="border border-black relative group bg-white">
                <div class="h-32 overflow-hidden border-b border-black">
                    <img :src="'/storage/' + item.image" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                </div>
                <div class="p-4">
                    <div class="text-xs font-bold uppercase tracking-tight line-clamp-1">{{ item.title }}</div>
                </div>
                <button @click="deletePortfolio(item.id)" class="absolute top-2 right-2 bg-[#E8192C] text-white w-6 h-6 flex items-center justify-center border border-black text-[10px] hover:bg-white hover:text-[#E8192C] transition-all">
                    ×
                </button>
            </div>
            <div v-if="portfolios.length === 0" class="col-span-3 py-12 text-center text-gray-400 italic font-medium uppercase tracking-widest text-[10px]">
                Belum ada item portfolio
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import { useAuth } from '../../composables/useAuth'
import { useApi } from '../../composables/useApi'

const { user, isFreelancer } = useAuth()
const { get, post, del } = useApi()

const loading = ref(false)
const success = ref(false)
const avatarPreview = ref(null)
const avatarFile = ref(null)

const form = ref({
    name: user.value?.name || '',
    phone: user.value?.phone || '',
    bio: user.value?.bio || ''
})

const stats = ref({ services: 0, bookings: 0, rating: '0.0' })
const portfolios = ref([])
const showAddPortfolio = ref(false)
const portfolioPreview = ref(null)
const portfolioForm = ref({ title: '', description: '', image: null })

const fetchData = async () => {
    try {
        const [userRes, portfoliosRes] = await Promise.all([
            get('/profile'),
            isFreelancer.value ? get('/portfolios') : Promise.resolve({ data: [] })
        ])
        
        const userData = userRes.data
        form.value = {
            name: userData.name,
            phone: userData.phone || '',
            bio: userData.bio || ''
        }
        
        if (isFreelancer.value) {
            portfolios.value = portfoliosRes.data
            // Assuming we added some logic in the controller or model for stats
            stats.value = {
                services: userData.services_count || 0,
                bookings: userData.freelancer_bookings_count || 0,
                rating: userData.avg_rating || '0.0'
            }
        }
    } catch (error) {
        console.error('Failed to fetch profile data', error)
    }
}

const handleAvatar = (e) => {
    const file = e.target.files[0]
    if (file) {
        avatarFile.value = file
        avatarPreview.value = URL.createObjectURL(file)
    }
}

const clearAvatar = () => {
    avatarFile.value = null
    avatarPreview.value = null
}

const updateProfile = async () => {
    loading.value = true
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('phone', form.value.phone)
    formData.append('bio', form.value.bio)
    if (avatarFile.value) {
        formData.append('avatar', avatarFile.value)
    }

    try {
        await post('/profile', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        success.value = true
        setTimeout(() => { success.value = false }, 2000)
        router.reload({ only: ['auth'] })
    } catch (error) {
        alert('Gagal memperbarui profil')
    } finally {
        loading.value = false
    }
}

const handlePortfolioFile = (e) => {
    const file = e.target.files[0]
    if (file) {
        portfolioForm.value.image = file
        portfolioPreview.value = URL.createObjectURL(file)
    }
}

const savePortfolio = async () => {
    const formData = new FormData()
    formData.append('title', portfolioForm.value.title)
    formData.append('description', portfolioForm.value.description)
    formData.append('image', portfolioForm.value.image)

    try {
        const res = await post('/portfolios', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        portfolios.value.unshift(res.data)
        portfolioForm.value = { title: '', description: '', image: null }
        portfolioPreview.value = null
        showAddPortfolio.value = false
    } catch (error) {
        alert('Gagal menyimpan portfolio')
    }
}

const deletePortfolio = async (id) => {
    if (!confirm('Hapus item portfolio ini?')) return
    try {
        await del(`/portfolios/${id}`)
        portfolios.value = portfolios.value.filter(p => p.id !== id)
    } catch (error) {
        alert('Gagal menghapus portfolio')
    }
}

onMounted(fetchData)
</script>
