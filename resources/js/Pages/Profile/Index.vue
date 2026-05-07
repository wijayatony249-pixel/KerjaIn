<template>
  <AppLayout title="Profil Saya">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left: Profile Card -->
      <div>
        <div class="bg-white/[0.03] border border-white/10 p-8 rounded-lg text-center backdrop-blur-xl sticky top-8">
          <div class="relative inline-block mb-6 group cursor-pointer" @click="$refs.avatarInput.click()">
            <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-[#FF3366] to-[#6C63FF] flex items-center justify-center text-white text-4xl font-black shadow-xl shadow-[#FF3366]/20 overflow-hidden">
              <img v-if="user?.avatar" :src="'/storage/' + user.avatar" class="w-full h-full object-cover" />
              <span v-else>{{ userInitials }}</span>
            </div>
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity border-2 border-dashed border-white/50">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>

            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-4 border-[#0a0a0a] rounded-full"></div>
            
            <!-- Hidden Input -->
            <input type="file" ref="avatarInput" class="hidden" accept="image/*" @change="handleAvatarUpload" />
          </div>
          
          <h2 class="text-2xl font-black uppercase tracking-tighter mb-1">{{ user?.name }}</h2>
          <div class="inline-block px-4 py-1 bg-[#FF3366]/10 text-[#FF3366] text-[10px] font-black uppercase tracking-widest rounded-full mb-8 border border-[#FF3366]/20">
            {{ user?.role }}
          </div>
          
          <div class="space-y-6 text-left border-t border-white/5 pt-8">
            <div class="group">
              <div class="text-white/20 text-[8px] font-black uppercase tracking-widest mb-1 group-hover:text-[#FF3366] transition-colors">Email</div>
              <div class="text-sm font-bold text-white/80">{{ user?.email }}</div>
            </div>
            <div class="group">
              <div class="text-white/20 text-[8px] font-black uppercase tracking-widest mb-1 group-hover:text-[#FF3366] transition-colors">Telepon</div>
              <div class="text-sm font-bold text-white/80">{{ user?.phone || '-' }}</div>
            </div>
            <div class="group">
              <div class="text-white/20 text-[8px] font-black uppercase tracking-widest mb-1 group-hover:text-[#FF3366] transition-colors">Bergabung</div>
              <div class="text-sm font-bold text-white/80">{{ formatDate(user?.created_at) }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Edit Form & Portfolio -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Edit Form -->
        <div class="bg-white/[0.03] border border-white/10 p-10 rounded-lg">
          <h3 class="text-xl font-black uppercase tracking-tighter mb-10 flex items-center gap-4">
            <span class="w-8 h-8 rounded-md bg-white/5 flex items-center justify-center text-sm">⚙️</span>
            Informasi Profil
          </h3>
          
          <form @submit.prevent="updateProfile" class="space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <label class="block text-white/40 text-[10px] font-black mb-3 uppercase tracking-widest">Nama Lengkap</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="w-full bg-white/5 border border-white/10 rounded-md px-5 py-4 text-sm text-white focus:outline-none focus:border-[#FF3366]/50 transition-all"
                  required
                />
              </div>
              <div>
                <label class="block text-white/40 text-[10px] font-black mb-3 uppercase tracking-widest">No. Telepon</label>
                <input
                  v-model="form.phone"
                  type="text"
                  class="w-full bg-white/5 border border-white/10 rounded-md px-5 py-4 text-sm text-white focus:outline-none focus:border-[#FF3366]/50 transition-all"
                  placeholder="Contoh: 08123456789"
                />
              </div>
            </div>

            <!-- Modern Category Selector -->
            <div v-if="user?.role === 'freelancer'">
              <label class="block text-white/40 text-[10px] font-black mb-4 uppercase tracking-widest text-center">Pilih Kategori Keahlian Anda</label>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <button
                  v-for="cat in categories"
                  :key="cat"
                  type="button"
                  @click="form.category = cat"
                  :class="[
                    'px-4 py-4 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all duration-300 border',
                    form.category === cat 
                      ? 'bg-[#FF3366] border-[#FF3366] text-white shadow-lg shadow-[#FF3366]/20 scale-105' 
                      : 'bg-white/5 border-white/5 text-white/40 hover:border-white/20 hover:text-white'
                  ]"
                >
                  {{ cat }}
                </button>
              </div>
            </div>

            <div>
              <label class="block text-white/40 text-[10px] font-black mb-3 uppercase tracking-widest">Bio Singkat</label>
              <textarea
                v-model="form.bio"
                rows="4"
                class="w-full bg-white/5 border border-white/10 rounded-md px-5 py-4 text-sm text-white focus:outline-none focus:border-[#FF3366]/50 transition-all resize-none"
                placeholder="Ceritakan sedikit tentang dirimu..."
              ></textarea>
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="w-full bg-[#FF3366] text-white py-5 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#e02d59] shadow-lg shadow-[#FF3366]/20 transition-all disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </form>
        </div>

        <!-- Portfolio Section -->
        <div v-if="user?.role === 'freelancer'" class="bg-white/[0.03] border border-white/10 p-10 rounded-lg">
          <div class="flex items-center justify-between mb-10">
            <h3 class="text-xl font-black uppercase tracking-tighter flex items-center gap-4">
              <span class="w-8 h-8 rounded-md bg-white/5 flex items-center justify-center text-sm">🎨</span>
              Karya Terbaru
            </h3>
            <button 
              @click="showPortfolioForm = !showPortfolioForm"
              :class="[
                'text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-md transition-all',
                showPortfolioForm ? 'bg-white/10 text-white' : 'bg-[#FF3366]/10 text-[#FF3366] hover:bg-[#FF3366] hover:text-white'
              ]"
            >
              {{ showPortfolioForm ? 'Batal' : '+ Tambah Project' }}
            </button>
          </div>

          <!-- Add Portfolio Form -->
          <div v-if="showPortfolioForm" class="mb-12 p-8 border-2 border-dashed border-white/10 rounded-lg bg-white/[0.01]">
            <form @submit.prevent="addPortfolio" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-white/40 text-[8px] font-black mb-2 uppercase tracking-widest">Judul Project</label>
                  <input v-model="portfolioForm.title" type="text" class="w-full bg-white/5 border border-white/10 rounded-md p-3 text-xs text-white outline-none focus:border-[#FF3366]/50" required />
                </div>
                <div>
                  <label class="block text-white/40 text-[8px] font-black mb-2 uppercase tracking-widest">File Gambar</label>
                  <input type="file" @change="e => portfolioForm.image = e.target.files[0]" class="w-full text-[10px] text-white/40" required />
                </div>
              </div>
              <button type="submit" class="bg-white text-black px-8 py-3 rounded-md text-[10px] font-black uppercase tracking-widest hover:bg-[#FF3366] hover:text-white transition-all">Simpan Project</button>
            </form>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div v-for="item in portfolios" :key="item.id" class="bg-white/5 border border-white/5 rounded-lg overflow-hidden group relative hover:border-[#FF3366]/30 transition-all">
              <div class="aspect-square overflow-hidden bg-[#0a0a0a]">
                <img :src="item.image_url" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
              </div>
              <div class="p-4">
                <div class="text-[10px] font-black uppercase tracking-tighter truncate text-white/80">{{ item.title }}</div>
              </div>
              <button 
                @click="deletePortfolio(item.id)"
                class="absolute top-3 right-3 w-8 h-8 bg-[#FF3366] text-white rounded-md flex items-center justify-center font-bold opacity-0 group-hover:opacity-100 transition-all hover:scale-110 shadow-xl"
              >
                ×
              </button>
            </div>
            <div v-if="portfolios.length === 0" class="col-span-3 py-16 text-center border-2 border-dashed border-white/5 rounded-2xl">
              <p class="text-white/20 text-[10px] font-black uppercase tracking-widest">Belum ada karya untuk ditampilkan</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import { useAuth } from '../../composables/useAuth';
import { useToast } from '../../composables/useToast';

const { user, axiosAuth, fetchUser } = useAuth();
const { addToast } = useToast();
const portfolios = ref([]);
const showPortfolioForm = ref(false);

const categories = ['Desain', 'Fotografi', 'Web Dev', 'Video', 'Penulisan', 'Musik'];

const form = useForm({
  name: '',
  phone: '',
  bio: '',
  category: '',
});

const portfolioForm = useForm({
  title: '',
  image: null,
});

const userInitials = computed(() => {
  if (!user.value?.name) return '?';
  return user.value.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
});

const fetchData = async () => {
  if (user.value?.role === 'freelancer') {
    try {
      const res = await axiosAuth().get('/portfolios');
      portfolios.value = res.data;
    } catch (error) {
      console.error('Failed to fetch portfolios');
    }
  }
};

onMounted(async () => {
  if (!user.value) await fetchUser();
  
  if (user.value) {
    form.name = user.value.name || '';
    form.phone = user.value.phone || '';
    form.bio = user.value.bio || '';
    form.category = user.value.skills || '';
    fetchData();
  }
});

const avatarInput = ref(null);

const handleAvatarUpload = async (e) => {
  const file = e.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('avatar', file);

  try {
    await axiosAuth().post('/me/avatar', formData);
    fetchUser(); // Refresh user data to show new avatar
    addToast('Foto profil berhasil diperbarui!');
  } catch (error) {
    addToast('Gagal mengunggah foto profil.', 'error');
  }
};

const updateProfile = async () => {
  try {
    await axiosAuth().put('/me', {
        name: form.name,
        phone: form.phone,
        bio: form.bio,
        skills: form.category
    });
    addToast('Profil berhasil diperbarui!');
    fetchUser();
  } catch (error) {
    addToast('Gagal memperbarui profil.', 'error');
  }
};

const addPortfolio = async () => {
  const formData = new FormData();
  formData.append('title', portfolioForm.title);
  formData.append('image', portfolioForm.image);

  try {
    await axiosAuth().post('/portfolios', formData);
    portfolioForm.reset();
    showPortfolioForm.value = false;
    fetchData();
    addToast('Portfolio berhasil ditambahkan!');
  } catch (error) {
    addToast('Gagal menambah portfolio.', 'error');
  }
};

const deletePortfolio = async (id) => {
  if (confirm('Hapus portfolio ini?')) {
    try {
      await axiosAuth().delete(`/portfolios/${id}`);
      fetchData();
      addToast('Portfolio berhasil dihapus!');
    } catch (error) {
      addToast('Gagal menghapus portfolio.', 'error');
    }
  }
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
};
</script>
