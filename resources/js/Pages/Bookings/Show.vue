<template>
  <AppLayout title="Detail Booking">
    <div class="mb-8">
      <Link href="/booking" class="text-white/40 text-xs font-bold uppercase tracking-widest hover:text-white flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Booking
      </Link>
    </div>

    <div v-if="booking" class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start relative z-10">
      <!-- Left: Info & Chat -->
      <div class="lg:col-span-3 space-y-6">
        <div class="bg-white/5 border border-white/10 p-8 rounded-2xl backdrop-blur-xl">
          <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-black uppercase tracking-tighter">Booking #{{ booking.id }}</h2>
            <StatusBadge :status="booking.status" />
          </div>

          <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
              <div class="text-[10px] font-bold uppercase tracking-widest text-white/30 mb-1">Layanan</div>
              <div class="font-black text-xl uppercase tracking-tight text-[#FF3366]">{{ booking.service?.title }}</div>
            </div>
            <div>
              <div class="text-[10px] font-bold uppercase tracking-widest text-white/30 mb-1">Tanggal Booking</div>
              <div class="font-black text-xl uppercase tracking-tight text-white">{{ formatDate(booking.booking_date) }}</div>
            </div>
            <div>
              <div class="text-[10px] font-bold uppercase tracking-widest text-white/30 mb-1">Total Harga</div>
              <div class="font-black text-xl text-white uppercase tracking-tight">Rp {{ formatPrice(booking.service?.price) }}</div>
            </div>
            <div>
              <div class="text-[10px] font-bold uppercase tracking-widest text-white/30 mb-1">Status Pembayaran</div>
              <div :class="['font-black text-sm uppercase tracking-widest px-3 py-1 rounded-full inline-block', booking.payment_status === 'paid' ? 'bg-green-500/20 text-green-500' : 'bg-yellow-500/20 text-yellow-500']">
                {{ booking.payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
              </div>
            </div>
          </div>

          <div class="border-t border-white/5 pt-8 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-[#FF3366] to-[#6C63FF] text-white text-xs font-black flex items-center justify-center">
              {{ (isFreelancer ? booking.client?.name : booking.freelancer?.name)?.[0] }}
            </div>
            <div>
              <div class="text-[10px] font-bold uppercase tracking-widest text-white/30">
                {{ isFreelancer ? 'Client' : 'Freelancer' }}
              </div>
              <div class="font-black uppercase tracking-tight text-white">
                {{ isFreelancer ? booking.client?.name : booking.freelancer?.name }}
              </div>
            </div>
          </div>
        </div>

        <!-- Chat Section -->
        <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden flex flex-col h-[600px] backdrop-blur-xl">
          <div class="p-6 border-b border-white/5 flex items-center justify-between bg-white/[0.02]">
            <h3 class="font-black uppercase tracking-widest text-xs">Pesan Diskusi</h3>
            <div class="flex items-center gap-2 bg-green-500/10 px-3 py-1 rounded-md border border-green-500/20">
                <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-[8px] font-black text-green-500 uppercase tracking-widest">Enkripsi Aktif</span>
            </div>
          </div>

          <div class="flex-1 overflow-y-auto p-8 space-y-6" ref="messageContainer">
            <div 
              v-for="msg in messages" 
              :key="msg.id"
              :class="['flex flex-col max-w-[85%]', msg.sender_id === user.id ? 'ml-auto items-end' : 'mr-auto items-start']"
            >
              <div 
                :class="[
                  'px-6 py-4 text-sm font-medium rounded-lg transition-all duration-300 shadow-xl',
                  msg.sender_id === user.id 
                    ? 'bg-[#FF3366] text-white rounded-tr-none shadow-[#FF3366]/20' 
                    : 'bg-white/10 text-white rounded-tl-none border border-white/5'
                ]"
              >
                {{ msg.message }}
              </div>
              <div class="text-[8px] font-bold text-white/20 uppercase tracking-widest mt-2 px-1">
                {{ formatDateShort(msg.created_at) }}
              </div>
            </div>
            <div v-if="messages.length === 0" class="h-full flex flex-col items-center justify-center opacity-20">
                <svg class="w-16 h-16 mb-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <div class="text-[10px] font-black uppercase tracking-[0.4em]">Belum ada pesan</div>
            </div>
          </div>

          <div class="p-6 border-t border-white/5 bg-white/[0.02] flex gap-4">
            <input 
              v-model="newMessage" 
              @keyup.enter="sendMessage"
              type="text" 
              placeholder="Ketik pesan untuk didiskusikan..."
              class="flex-1 bg-white/5 border border-white/10 rounded-lg px-6 py-4 text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all text-white"
            >
            <button 
              @click="sendMessage"
              :disabled="!newMessage.trim()"
              class="bg-[#FF3366] text-white font-black px-8 rounded-lg hover:bg-[#e02d59] transition-all duration-300 uppercase tracking-widest text-[10px] shadow-lg shadow-[#FF3366]/20 disabled:opacity-30"
            >
              Kirim
            </button>
          </div>
        </div>
      </div>

      <!-- Right: Status & Actions -->
      <div class="lg:col-span-2 space-y-6 sticky top-24">
        <div class="bg-white/5 border border-white/10 p-8 rounded-2xl backdrop-blur-xl">
          <h3 class="font-black uppercase tracking-widest text-[10px] text-white/30 mb-8">Alur Pengerjaan</h3>
          <div class="space-y-10 relative">
            <div class="absolute left-[7px] top-2 bottom-2 w-0.5 bg-white/5"></div>
            
            <div v-for="step in timelineSteps" :key="step.label" class="flex gap-6 relative z-10">
              <div 
                :class="[
                  'w-4 h-4 rounded-full border-2 shrink-0 mt-0.5 transition-all duration-500',
                  step.active ? 'bg-[#FF3366] border-[#FF3366] shadow-[0_0_15px_rgba(255,51,102,0.5)]' : 'bg-[#0a0a0a] border-white/10'
                ]"
              ></div>
              <div>
                <div :class="['text-sm font-black uppercase tracking-tight', step.active ? 'text-white' : 'text-white/20']">
                  {{ step.label }}
                </div>
                <div v-if="step.time" class="text-[8px] font-black text-white/20 uppercase tracking-widest mt-1">
                  {{ step.time }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Card -->
        <div v-if="canAction" class="bg-gradient-to-br from-[#FF3366] to-[#e02d59] p-8 rounded-2xl shadow-2xl shadow-[#FF3366]/20 text-white">
          <template v-if="isFreelancer && booking.status === 'pending'">
            <div class="font-black text-2xl uppercase tracking-tighter mb-2">Booking Baru</div>
            <p class="text-white/80 text-[10px] mb-8 font-bold uppercase tracking-widest">Segera konfirmasi ketersediaan Anda untuk proyek ini.</p>
            <div class="space-y-3">
              <button @click="updateStatus('accepted')" class="w-full bg-white text-[#FF3366] font-black py-4 rounded-lg hover:bg-white/90 transition-all uppercase tracking-widest text-xs">
                Terima Proyek
              </button>
              <button @click="updateStatus('rejected')" class="w-full bg-black/20 text-white font-black py-4 rounded-lg hover:bg-black/30 transition-all border border-white/10 uppercase tracking-widest text-xs">
                Tolak
              </button>
            </div>
          </template>

          <template v-if="isFreelancer && booking.status === 'accepted'">
            <div class="font-black text-2xl uppercase tracking-tighter mb-2">Pekerjaan Aktif</div>
            <p class="text-white/80 text-[10px] mb-8 font-bold uppercase tracking-widest">Tandai sebagai selesai setelah seluruh aset terkirim.</p>
            <button @click="updateStatus('done')" class="w-full bg-white text-black font-black py-5 rounded-lg hover:bg-white/90 transition-all uppercase tracking-widest text-sm shadow-xl">
              Konfirmasi Selesai
            </button>
          </template>

          <template v-if="!isFreelancer && booking.payment_status === 'unpaid'">
            <div class="font-black text-2xl uppercase tracking-tighter mb-2">Selesaikan Pembayaran</div>
            <p class="text-white/80 text-[10px] mb-8 font-bold uppercase tracking-widest">Silakan bayar agar proyek bisa segera diproses oleh freelancer.</p>
            <button @click="payNow" class="w-full bg-white text-black font-black py-5 rounded-lg hover:bg-white/90 transition-all uppercase tracking-widest text-sm shadow-xl">
              Bayar Sekarang
            </button>
          </template>

          <template v-if="!isFreelancer && booking.status === 'done' && !booking.review">
            <div class="font-black text-2xl uppercase tracking-tighter mb-8 text-center">Beri Ulasan</div>
            <div class="space-y-6">
              <div class="flex justify-center gap-3">
                <button 
                  v-for="star in 5" :key="star"
                  @click="reviewForm.rating = star"
                  :class="['text-3xl transition-all', reviewForm.rating >= star ? 'text-yellow-400 drop-shadow-[0_0_8px_rgba(250,204,21,0.5)] scale-110' : 'text-white/20 hover:text-white/40']"
                >
                  ★
                </button>
              </div>
              <textarea 
                v-model="reviewForm.comment"
                placeholder="Apa pendapatmu tentang hasil kerja freelancer?"
                class="w-full bg-black/20 border border-white/10 rounded-lg p-4 text-sm focus:outline-none focus:border-white/40 text-white resize-none h-32 placeholder:text-white/30"
              ></textarea>
              <button @click="submitReview" class="w-full bg-white text-black font-black py-5 rounded-lg hover:bg-white/90 transition-all uppercase tracking-widest text-sm shadow-xl">
                Kirim Ulasan
              </button>
            </div>
          </template>
        </div>

        <!-- Submitted Review -->
        <div v-if="booking.review" class="bg-white/5 border border-white/10 p-8 rounded-2xl backdrop-blur-xl">
          <h3 class="font-black uppercase tracking-widest text-[10px] text-white/30 mb-6">Ulasan Client</h3>
          <div class="flex text-yellow-400 text-xl mb-3 drop-shadow-[0_0_8px_rgba(250,204,21,0.3)]">
            <span v-for="s in booking.review.rating" :key="s">★</span>
          </div>
          <p class="text-sm font-medium italic text-white/60 leading-relaxed">"{{ booking.review.comment }}"</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, nextTick } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import StatusBadge from '../../Components/StatusBadge.vue'
import { useAuth } from '../../composables/useAuth'
import { useApi } from '../../composables/useApi'

const props = defineProps({
  booking: Object
})

const { user, isFreelancer } = useAuth()
const { get, post, put } = useApi()

const booking = ref(null)
const messages = ref([])
const newMessage = ref('')
const messageContainer = ref(null)
const pollInterval = ref(null)

const reviewForm = ref({
    rating: 5,
    comment: ''
})

const fetchBooking = async () => {
  const url = window.location.pathname
  const id = url.split('/').pop()
  try {
    const res = await get(`/bookings/${id}`)
    booking.value = res.data
    messages.value = res.data.messages || []
    scrollToBottom()
    
    // Subscribe to realtime messages
    subscribeToEcho()
  } catch (error) {
    console.error('Failed to fetch booking', error)
  }
}

const isSubscribed = ref(false)
const subscribeToEcho = () => {
  if (isSubscribed.value || !booking.value) return
  
  window.Echo.private(`bookings.${booking.value.id}`)
    .listen('.message.sent', (e) => {
      // Prevent duplicate messages if sender (compare as numbers)
      if (Number(e.message.sender_id) !== Number(user.value?.id)) {
          messages.value.push(e.message)
          scrollToBottom()
      }
    })
  
  isSubscribed.value = true
}

const sendMessage = async () => {
  if (!newMessage.value.trim()) return
  const text = newMessage.value
  newMessage.value = ''
  
  try {
    const res = await post('/messages', {
      booking_id: booking.value.id,
      message: text
    })
    messages.value.push(res.data)
    scrollToBottom()
  } catch (error) {
    alert('Gagal mengirim pesan')
  }
}

const updateStatus = async (status) => {
  if (!confirm(`Yakin ingin mengubah status pesanan?`)) return
  try {
    await put(`/bookings/${booking.value.id}`, { status })
    fetchBooking()
  } catch (error) {
    alert('Gagal memperbarui status')
  }
}

const submitReview = async () => {
  try {
    await post('/reviews', {
        booking_id: booking.value.id,
        ...reviewForm.value
    })
    fetchBooking()
  } catch (error) {
    alert('Gagal mengirim ulasan')
  }
}

const payNow = () => {
  if (!booking.value.snap_token) {
    alert('Token pembayaran belum siap. Silakan refresh halaman.')
    return
  }

  window.snap.pay(booking.value.snap_token, {
    onSuccess: function(result) {
      alert("Pembayaran Berhasil!")
      fetchBooking()
    },
    onPending: function(result) {
      alert("Menunggu Pembayaran...")
      fetchBooking()
    },
    onError: function(result) {
      alert("Pembayaran Gagal!")
    },
    onClose: function() {
      alert('Kamu menutup popup tanpa menyelesaikan pembayaran.')
    }
  })
}

const timelineSteps = computed(() => {
  if (!booking.value) return []
  return [
    { label: 'Booking Diterima', active: true, time: formatDateShort(booking.value.created_at) },
    { label: 'Konfirmasi Freelancer', active: ['accepted', 'done'].includes(booking.value.status), time: booking.value.status !== 'pending' ? 'Terverifikasi' : null },
    { label: 'Proyek Selesai', active: booking.value.status === 'done', time: booking.value.status === 'done' ? 'Tuntas' : null }
  ]
})

const canAction = computed(() => {
  if (!booking.value) return false
  if (isFreelancer.value) {
    return ['pending', 'accepted'].includes(booking.value.status) && booking.value.payment_status === 'paid'
  } else {
    return (booking.value.payment_status === 'unpaid') || (booking.value.status === 'done' && !booking.review)
  }
})

const scrollToBottom = () => {
  nextTick(() => {
    if (messageContainer.value) {
      messageContainer.value.scrollTop = messageContainer.value.scrollHeight
    }
  })
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

const formatDateShort = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ', ' + 
         date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
}

onMounted(() => {
  fetchBooking()

  // Load Midtrans Snap Script
  const snapScript = 'https://app.sandbox.midtrans.com/snap/snap.js'
  const clientKey = import.meta.env.VITE_MIDTRANS_CLIENT_KEY
  const script = document.createElement('script')
  script.src = snapScript
  script.setAttribute('data-client-key', clientKey)
  script.async = true
  document.head.appendChild(script)
})

onUnmounted(() => {
  if (booking.value) {
    window.Echo.leave(`bookings.${booking.value.id}`)
  }
})
</script>
