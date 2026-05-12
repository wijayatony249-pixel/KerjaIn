<template>
  <AppLayout title="Detail Layanan">
    <BackButton href="/layanan" label="Kembali ke Jelajah Layanan" />

    <div v-if="service" class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start relative z-10">
      <!-- Left: Content -->
      <div class="lg:col-span-2 space-y-16">
        <!-- Thumbnail & Header -->
        <div>
          <div class="border border-white/10 aspect-video bg-white/5 mb-10 overflow-hidden rounded-xl shadow-2xl shadow-black/50">
            <img v-if="service.thumbnail" :src="'/storage/' + service.thumbnail" class="w-full h-full object-cover">
            <div v-else class="w-full h-full flex items-center justify-center text-white/10 font-black uppercase tracking-[0.4em] text-xs">No Visual Data</div>
          </div>
          
          <div class="flex items-center gap-4 mb-6">
            <span class="bg-[#FF3366]/10 text-[#FF3366] text-[10px] px-4 py-1.5 rounded-md uppercase font-black border border-[#FF3366]/20 tracking-widest">
              {{ service.category }}
            </span>
            <div class="flex items-center gap-2">
                <div class="flex text-[#FF3366] text-xs">
                    <span v-for="i in 5" :key="i" class="mr-0.5">{{ i <= Math.round(service.avg_rating || 0) ? '★' : '☆' }}</span>
                </div>
                <span class="text-white/40 font-black text-[10px] uppercase tracking-tighter">{{ service.avg_rating || '0.0' }} ({{ service.reviews?.length || 0 }} Ulasan)</span>
            </div>
          </div>
          
          <h1 class="text-6xl font-black uppercase tracking-tighter leading-none mb-8 text-white">{{ service.title }}</h1>
          <p class="text-white/60 leading-relaxed whitespace-pre-wrap font-medium text-lg">{{ service.description }}</p>
        </div>

        <!-- Portfolio -->
        <div v-if="service.freelancer?.portfolios?.length">
          <h2 class="text-2xl font-black uppercase tracking-tighter mb-10 border-b border-white/5 pb-6 text-white">Project Highlights</h2>
          <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div v-for="p in service.freelancer.portfolios" :key="p.id" class="border border-white/10 aspect-square overflow-hidden rounded-lg group relative bg-white/5 transition-all hover:border-[#FF3366]/50">
                <img :src="'/storage/' + p.image" class="w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end p-6">
                    <div class="text-white">
                        <div class="text-[10px] font-black uppercase tracking-widest">{{ p.title }}</div>
                    </div>
                </div>
            </div>
          </div>
        </div>

        <!-- Reviews -->
        <div class="pb-20">
          <h2 class="text-2xl font-black uppercase tracking-tighter mb-10 border-b border-white/5 pb-6 text-white">Client Feedback</h2>
          <div class="space-y-6">
            <div v-for="review in service.reviews" :key="review.id" class="border border-white/5 p-8 bg-white/[0.02] rounded-xl backdrop-blur-sm">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-md bg-gradient-to-br from-[#FF3366] to-[#6C63FF] text-white text-xs font-black flex items-center justify-center border border-white/10">
                            {{ review.client?.name?.[0] }}
                        </div>
                        <div>
                            <div class="text-xs font-black uppercase tracking-widest text-white">{{ review.client?.name }}</div>
                            <div class="text-[#FF3366] text-[10px] mt-1">
                                <span v-for="i in 5" :key="i">{{ i <= review.rating ? '★' : '☆' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-[8px] font-black text-white/20 uppercase tracking-[0.2em]">
                        {{ formatDate(review.created_at) }}
                    </div>
                </div>
                <p class="text-white/60 font-medium leading-relaxed italic">"{{ review.comment }}"</p>
            </div>
            <div v-if="!service.reviews?.length" class="py-20 text-center border border-dashed border-white/5 rounded-xl bg-white/[0.01]">
                <div class="text-white/5 font-black uppercase tracking-[0.5em] text-[10px]">Belum Ada Ulasan</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Order Panel -->
      <div class="lg:col-span-1 sticky top-24">
        <div class="border border-white/10 bg-white/[0.03] backdrop-blur-2xl p-10 rounded-xl shadow-2xl shadow-[#FF3366]/5 relative overflow-hidden group">
          <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#FF3366]/5 rounded-full blur-3xl transition-all group-hover:bg-[#FF3366]/10"></div>
          
          <div class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] mb-4">Investasi Layanan</div>
          <div class="text-5xl font-black mb-10 text-white tracking-tighter">
            <span class="text-[#FF3366] text-xl mr-2">RP</span>{{ formatPrice(service.price) }}
          </div>
          
          <div class="space-y-5 mb-10">
            <div v-for="feature in ['Unlimited Revisions', 'Turbo Delivery', 'Master Source Files']" :key="feature" class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-white/60">
                <div class="w-5 h-5 rounded-md bg-[#FF3366]/10 flex items-center justify-center text-[#FF3366]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                {{ feature }}
            </div>
          </div>

          <button 
            v-if="!isMyService"
            @click="showOrderModal = true"
            class="w-full bg-[#FF3366] text-white font-black py-5 rounded-md hover:bg-[#e02d59] transition-all uppercase tracking-[0.2em] text-xs shadow-xl shadow-[#FF3366]/20 active:scale-95 mb-10"
          >
            Pesan Jasa Sekarang
          </button>
          
          <div class="flex items-center gap-5 pt-10 border-t border-white/5">
            <div class="w-14 h-14 rounded-md bg-white/5 p-1 border border-white/10">
                <div class="w-full h-full bg-gradient-to-br from-white/10 to-transparent rounded-sm flex items-center justify-center text-white font-black text-lg">
                    {{ service.freelancer?.name?.[0] }}
                </div>
            </div>
            <div>
                <div class="text-[8px] font-black text-white/20 uppercase tracking-[0.3em] mb-1">Elite Freelancer</div>
                <div class="font-black uppercase tracking-tight text-white">{{ service.freelancer?.name }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Modal -->
    <Transition name="modal">
      <div v-if="showOrderModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-xl">
          <div class="bg-[#0f0f0f] border border-white/10 p-12 rounded-xl w-full max-w-xl shadow-2xl relative overflow-hidden">
              <div class="absolute top-0 right-0 w-64 h-64 bg-[#FF3366]/5 rounded-full blur-[100px] pointer-events-none"></div>
              
              <div class="flex justify-between items-start mb-12">
                  <div>
                    <h2 class="text-4xl font-black uppercase tracking-tighter text-white mb-2">Konfirmasi</h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-widest">Selesaikan detail pesanan Anda</p>
                  </div>
                  <button @click="showOrderModal = false" class="text-white/20 hover:text-white transition-all p-2 hover:bg-white/5 rounded-md z-30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                  </button>
              </div>

              <div class="space-y-8 mb-12">
                  <div>
                      <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-white/40 mb-3">Target Pengerjaan</label>
                      <input v-model="orderForm.booking_date" type="date" class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all">
                  </div>
                  <div>
                      <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-white/40 mb-3">Brief / Catatan Tambahan</label>
                      <textarea v-model="orderForm.note" rows="5" placeholder="Detail keinginan Anda untuk project ini..." class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all resize-none"></textarea>
                  </div>
              </div>

              <div class="bg-white/5 border border-white/5 p-8 rounded-md mb-12 flex justify-between items-center">
                  <span class="text-[10px] font-black uppercase tracking-widest text-white/40">Total Investasi</span>
                  <span class="text-[#FF3366] text-2xl font-black tracking-tighter">Rp {{ formatPrice(service.price) }}</span>
              </div>

              <button 
                  @click="submitOrder"
                  :disabled="!orderForm.booking_date || loading"
                  class="w-full bg-[#FF3366] text-white font-black py-5 rounded-md hover:bg-[#e02d59] transition-all uppercase tracking-[0.2em] text-xs shadow-2xl shadow-[#FF3366]/30 disabled:opacity-20 active:scale-95"
              >
                  {{ loading ? 'Sistem Sedang Memproses...' : 'Konfirmasi & Kirim Pesanan' }}
              </button>
          </div>
      </div>
    </Transition>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import BackButton from '../../Components/BackButton.vue'
import StatusBadge from '../../Components/StatusBadge.vue'
import { useAuth } from '../../composables/useAuth'
import { useApi } from '../../composables/useApi'

const { user } = useAuth()
const { get, post } = useApi()

const service = ref(null)
const showOrderModal = ref(false)
const loading = ref(false)
const orderForm = ref({
    booking_date: '',
    note: ''
})

const fetchService = async () => {
  const id = window.location.pathname.split('/').pop()
  try {
    const res = await get(`/services/${id}`)
    service.value = res.data
  } catch (error) {
    console.error('Failed to fetch service', error)
  }
}

const isMyService = computed(() => {
    return service.value?.freelancer_id === user.value?.id
})

const submitOrder = async () => {
    loading.value = true
    try {
        await post('/bookings', {
            service_id: service.value.id,
            freelancer_id: service.value.freelancer_id,
            ...orderForm.value
        })
        showOrderModal.value = false
        router.visit('/booking')
    } catch (error) {
        alert('Gagal melakukan pemesanan')
    } finally {
        loading.value = false
    }
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price || 0)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

onMounted(fetchService)
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(20px);
}

input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    opacity: 0.5;
    cursor: pointer;
}
</style>
