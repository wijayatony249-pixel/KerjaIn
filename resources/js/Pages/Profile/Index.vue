<template>
  <AppLayout title="Profil Saya">
    <div class="relative z-10">
      <BackButton href="/dashboard" label="Kembali ke Dashboard" />
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
        <!-- Left: Profile Card -->
        <div class="lg:col-span-1 space-y-8">
          <div class="bg-white/5 border border-white/10 p-10 rounded-xl backdrop-blur-xl relative overflow-hidden text-center group">
            <!-- Background Glow -->
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-[#FF3366]/5 rounded-full blur-[60px] group-hover:bg-[#FF3366]/10 transition-all duration-700"></div>
            
            <div class="relative mb-8 inline-block">
              <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-[#FF3366] to-[#6C63FF] p-1">
                <div class="w-full h-full bg-[#0a0a0a] rounded-[10px] flex items-center justify-center overflow-hidden">
                  <img v-if="user.avatar_url" :src="user.avatar_url" class="w-full h-full object-cover">
                  <span v-else class="text-4xl font-black text-white">{{ user.name?.[0] }}</span>
                </div>
              </div>
              <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-4 border-[#0a0a0a] animate-pulse"></div>
            </div>
            
            <h2 class="text-3xl font-black uppercase tracking-tighter mb-2 text-white">{{ user.name }}</h2>
            <div class="bg-[#FF3366]/10 text-[#FF3366] text-[10px] font-black uppercase tracking-[0.3em] px-4 py-1.5 rounded-md border border-[#FF3366]/20 mb-6 inline-block">
              {{ user.role }}
            </div>
            <p class="text-white/40 text-xs font-medium uppercase tracking-widest mb-10">{{ user.email }}</p>
            
            <div class="space-y-6 text-left pt-10 border-t border-white/5">
              <div class="flex gap-4 items-center group/item">
                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-white/20 group-hover/item:bg-[#FF3366]/10 group-hover/item:text-[#FF3366] transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1a11 11 0 01-11-11V5z"/></svg>
                </div>
                <div class="text-sm font-black text-white/60 uppercase tracking-tight">{{ user.phone || 'Non-Verifikasi' }}</div>
              </div>
              <div class="flex gap-4 items-start group/item">
                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-white/20 group-hover/item:bg-[#FF3366]/10 group-hover/item:text-[#FF3366] transition-all shrink-0">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-xs font-medium text-white/40 leading-relaxed uppercase tracking-tight line-clamp-3">{{ user.bio || 'Belum ada bio operator.' }}</div>
              </div>
            </div>

            <div v-if="isFreelancer" class="grid grid-cols-3 bg-white/[0.02] border border-white/5 w-full mt-12 rounded-xl overflow-hidden">
              <div class="p-6 border-r border-white/5">
                  <div class="text-xl font-black text-white tracking-tighter">{{ stats.services }}</div>
                  <div class="text-[8px] font-black text-white/20 uppercase tracking-widest mt-1">Services</div>
              </div>
              <div class="p-6 border-r border-white/5">
                  <div class="text-xl font-black text-white tracking-tighter">{{ stats.bookings }}</div>
                  <div class="text-[8px] font-black text-white/20 uppercase tracking-widest mt-1">Bookings</div>
              </div>
              <div class="p-6">
                  <div class="text-xl font-black text-[#FF3366] tracking-tighter">{{ stats.rating }}</div>
                  <div class="text-[8px] font-black text-white/20 uppercase tracking-widest mt-1">Rating</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Form & Portfolio -->
        <div class="lg:col-span-2 space-y-12">
          <!-- Edit Profile Form -->
          <div class="bg-white/5 border border-white/10 p-10 rounded-xl backdrop-blur-xl relative overflow-hidden">
            <h2 class="text-2xl font-black uppercase tracking-tighter mb-10 text-white">Config Profil</h2>
            
            <div class="flex items-center gap-10 mb-12 pb-12 border-b border-white/5">
              <div class="w-24 h-24 bg-white/5 rounded-xl flex items-center justify-center border border-white/10 shrink-0 overflow-hidden relative group">
                  <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover">
                  <img v-else-if="user.avatar_url" :src="user.avatar_url" class="w-full h-full object-cover">
                  <span v-else class="text-2xl font-black text-white/10">{{ user.name?.[0] }}</span>
                  <div @click="$refs.avatarInput.click()" class="absolute inset-0 bg-[#FF3366]/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </div>
              </div>
              <div class="flex-1">
                  <div class="text-[10px] font-black uppercase tracking-[0.4em] text-white/30 mb-2">Avatar Identitas</div>
                  <div class="text-xs font-bold text-white mb-4 uppercase tracking-tight">Format JPG/PNG • Max 2MB</div>
                  <div class="flex gap-4">
                      <button @click="$refs.avatarInput.click()" class="bg-white text-black text-[10px] font-black px-6 py-2.5 rounded-md hover:bg-[#FF3366] hover:text-white transition-all uppercase tracking-widest shadow-lg shadow-black/20">Ganti Foto</button>
                      <button v-if="avatarPreview" @click="clearAvatar" class="flex items-center gap-2 text-[#FF3366] text-[10px] font-black uppercase tracking-widest hover:text-white transition-all group">
                        <div class="w-6 h-6 rounded bg-[#FF3366]/10 flex items-center justify-center group-hover:bg-[#FF3366]">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        Batalkan
                      </button>
                  </div>
                  <input type="file" ref="avatarInput" hidden @change="handleAvatar" accept="image/*">
              </div>
            </div>

            <form @submit.prevent="updateProfile" class="space-y-10">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                  <div class="md:col-span-2 space-y-4">
                      <label class="block text-[10px] font-black uppercase tracking-[0.4em] text-white/30">Nama Lengkap</label>
                      <input v-model="form.name" type="text" class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 focus:outline-none focus:border-[#FF3366]/50 text-white font-black uppercase tracking-tight text-lg transition-all">
                  </div>
                  <div class="space-y-4">
                      <label class="block text-[10px] font-black uppercase tracking-[0.4em] text-white/30">Email Operator</label>
                      <input :value="user.email" type="text" disabled class="w-full bg-white/[0.02] border border-white/5 rounded-md px-6 py-4 text-white/20 font-black uppercase tracking-tight text-sm cursor-not-allowed">
                  </div>
                  <div class="space-y-4">
                      <label class="block text-[10px] font-black uppercase tracking-[0.4em] text-white/30">No. Telepon</label>
                      <input v-model="form.phone" type="text" class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 focus:outline-none focus:border-[#FF3366]/50 text-white font-black uppercase tracking-tight text-sm transition-all">
                  </div>
                  <div class="md:col-span-2 space-y-4">
                      <label class="block text-[10px] font-black uppercase tracking-[0.4em] text-white/30">Biografi / Deskripsi Operator</label>
                      <textarea v-model="form.bio" rows="4" class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 focus:outline-none focus:border-[#FF3366]/50 text-white font-medium text-sm resize-none leading-relaxed transition-all"></textarea>
                  </div>
              </div>

              <div class="flex justify-end pt-4">
                <button type="submit" :disabled="loading" class="bg-[#FF3366] text-white font-black px-14 py-5 rounded-md hover:bg-[#e02d59] transition-all uppercase tracking-[0.3em] text-xs disabled:opacity-50 shadow-2xl shadow-[#FF3366]/20">
                  {{ loading ? 'Saving...' : (success ? '✓ Profile Updated' : 'Update Profile') }}
                </button>
              </div>
            </form>
          </div>

          <!-- Portfolio Section (Freelancer Only) -->
          <div v-if="isFreelancer" class="bg-white/5 border border-white/10 p-10 rounded-xl backdrop-blur-xl">
            <div class="flex items-center justify-between mb-10">
              <h2 class="text-2xl font-black uppercase tracking-tighter text-white">Project Showcase</h2>
              <button @click="showAddPortfolio = !showAddPortfolio" class="bg-white/5 text-white font-black px-6 py-2.5 rounded-md border border-white/10 hover:bg-[#FF3366] hover:border-[#FF3366] transition-all uppercase tracking-widest text-[10px]">
                {{ showAddPortfolio ? 'Cancel' : '+ Add Project' }}
              </button>
            </div>

            <!-- Add Portfolio Form -->
            <Transition name="slide-up">
              <div v-if="showAddPortfolio" class="mb-12 bg-white/[0.02] border border-white/10 p-8 rounded-xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-[#FF3366]"></div>
                
                <!-- Close Button -->
                <button @click="closePortfolioForm" class="absolute top-6 right-6 text-white/20 hover:text-white transition-all p-2 hover:bg-white/5 rounded-md z-30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <h3 class="font-black uppercase tracking-[0.3em] text-[10px] text-white/30 mb-8">Tambah Proyek Baru</h3>
                <div class="flex flex-col lg:flex-row gap-10 mb-10">
                    <div class="w-full lg:w-64 aspect-video border-2 border-dashed border-white/10 rounded-xl bg-white/[0.02] flex flex-col items-center justify-center cursor-pointer hover:bg-white/5 hover:border-[#FF3366]/50 transition-all group shrink-0 relative overflow-hidden" @click="$refs.portfolioInput.click()">
                        <img v-if="portfolioPreview" :src="portfolioPreview" class="absolute inset-0 w-full h-full object-cover">
                        <template v-else>
                            <svg class="w-10 h-10 text-white/10 group-hover:text-[#FF3366] transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div class="text-[8px] font-black text-white/20 mt-4 uppercase tracking-[0.4em]">Unggah Visual</div>
                        </template>
                        <input type="file" ref="portfolioInput" hidden @change="handlePortfolioFile" accept="image/*">
                    </div>
                    <div class="flex-1 space-y-6">
                        <div class="space-y-3">
                            <label class="text-[8px] font-black uppercase tracking-[0.4em] text-white/30">Nama Proyek</label>
                            <input v-model="portfolioForm.title" type="text" placeholder="Masukkan judul karya..." class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 text-sm focus:outline-none focus:border-[#FF3366]/50 text-white font-black uppercase tracking-tight transition-all">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[8px] font-black uppercase tracking-[0.4em] text-white/30">Keterangan Singkat</label>
                            <textarea v-model="portfolioForm.description" rows="2" placeholder="Detail singkat proses pengerjaan..." class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 text-sm focus:outline-none focus:border-[#FF3366]/50 text-white font-medium resize-none transition-all"></textarea>
                        </div>
                    </div>
                </div>
                <button @click="savePortfolio" :disabled="!portfolioForm.title || !portfolioForm.image" class="w-full bg-[#FF3366] text-white font-black py-4 rounded-md hover:bg-[#e02d59] transition-all uppercase tracking-[0.4em] text-[10px] disabled:opacity-20 shadow-xl shadow-[#FF3366]/20">
                  Save Project Ident
                </button>
              </div>
            </Transition>

            <!-- Portfolios Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              <div v-for="item in portfolios" :key="item.id" class="group bg-white/5 border border-white/10 rounded-xl relative overflow-hidden transition-all hover:border-[#FF3366]/40">
                  <div class="aspect-video overflow-hidden relative">
                      <img :src="'/storage/' + item.image" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700">
                      <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                  </div>
                  <div class="p-5">
                      <div class="text-[10px] font-black uppercase tracking-tight text-white group-hover:text-[#FF3366] transition-colors line-clamp-1">{{ item.title }}</div>
                  </div>
                  <button @click="confirmDeletePortfolio(item)" class="absolute top-3 right-3 bg-red-500/20 text-red-500 w-10 h-10 rounded-lg flex items-center justify-center border border-red-500/20 hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover:opacity-100 backdrop-blur-md shadow-lg shadow-red-500/20 z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
              </div>
              <div v-if="portfolios.length === 0" class="col-span-3 py-24 text-center text-white/10 font-black uppercase tracking-[0.5em] italic text-[10px] border-2 border-dashed border-white/5 rounded-xl">
                  Showcase Data Empty
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <ConfirmModal 
      :show="showDeleteModal"
      title="Hapus Proyek"
      message="Apakah Anda yakin ingin menghapus proyek ini dari portfolio? Tindakan ini tidak dapat dibatalkan."
      @confirm="executeDeletePortfolio"
      @close="showDeleteModal = false"
    />
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import BackButton from '../../Components/BackButton.vue'
import ConfirmModal from '../../Components/ConfirmModal.vue'
import { useAuth } from '../../composables/useAuth'
import { useApi } from '../../composables/useApi'
import { useToast } from '../../composables/useToast'

const { user, isFreelancer } = useAuth()
const { get, post, del } = useApi()
const { addToast } = useToast()

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

const showDeleteModal = ref(false)
const portfolioToDelete = ref(null)

const closePortfolioForm = () => {
    showAddPortfolio.value = false
    portfolioForm.value = { title: '', description: '', image: null }
    portfolioPreview.value = null
}

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
        addToast('Project showcase added successfully!')
    } catch (error) {
        addToast('Failed to add portfolio project', 'error')
    }
}

const confirmDeletePortfolio = (item) => {
    portfolioToDelete.value = item
    showDeleteModal.value = true
}

const executeDeletePortfolio = async () => {
    if (!portfolioToDelete.value) return
    try {
        await del(`/portfolios/${portfolioToDelete.value.id}`)
        portfolios.value = portfolios.value.filter(p => p.id !== portfolioToDelete.value.id)
        showDeleteModal.value = false
        portfolioToDelete.value = null
        addToast('Project removed from showcase')
    } catch (error) {
        addToast('Failed to delete project', 'error')
    }
}

onMounted(fetchData)
</script>
