<template>
  <AppLayout title="Detail Booking">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left: Info & Chat -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Booking Info Card -->
        <div class="bg-white border border-black p-8">
          <div class="flex items-center justify-between mb-8 pb-6 border-b border-black">
            <div>
              <div class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">PROYEK</div>
              <h2 class="text-3xl font-black uppercase tracking-tighter">{{ booking.service?.title }}</h2>
            </div>
            <div :class="['px-4 py-2 text-[10px] font-black uppercase tracking-widest', statusClass(booking.status)]">
              {{ booking.status }}
            </div>
          </div>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
              <div class="text-gray-400 text-[8px] font-bold uppercase tracking-widest mb-1">TANGGAL</div>
              <div class="text-sm font-black">{{ formatDate(booking.booking_date) }}</div>
            </div>
            <div>
              <div class="text-gray-400 text-[8px] font-bold uppercase tracking-widest mb-1">HARGA</div>
              <div class="text-sm font-black text-[#1A56FF]">Rp {{ formatNumber(booking.price) }}</div>
            </div>
            <div>
              <div class="text-gray-400 text-[8px] font-bold uppercase tracking-widest mb-1">ID</div>
              <div class="text-sm font-black">#{{ booking.id }}</div>
            </div>
          </div>

          <div class="mt-8 pt-8 border-t border-black">
            <div class="text-gray-400 text-[8px] font-bold uppercase tracking-widest mb-2">CATATAN</div>
            <p class="text-sm text-gray-600 leading-relaxed italic">"{{ booking.note || 'Tidak ada catatan.' }}"</p>
          </div>
        </div>

        <!-- Chat Panel -->
        <div class="bg-white border border-black flex flex-col h-[600px]">
          <div class="p-4 border-b border-black bg-gray-50 flex items-center justify-between">
            <div class="text-[10px] font-black uppercase tracking-widest">Diskusi Proyek</div>
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
          </div>

          <div ref="chatContainer" class="flex-1 overflow-y-auto p-6 space-y-4">
            <div 
              v-for="msg in messages" 
              :key="msg.id"
              :class="['flex', msg.sender_id === user?.id ? 'justify-end' : 'justify-start']"
            >
              <div 
                :class="[
                  'max-w-[70%] p-4 text-sm font-medium',
                  msg.sender_id === user?.id 
                    ? 'bg-[#1A56FF] text-white' 
                    : 'bg-gray-100 text-black border border-black'
                ]"
              >
                {{ msg.message }}
                <div :class="['text-[8px] mt-1 uppercase font-bold', msg.sender_id === user?.id ? 'text-white/60' : 'text-gray-400']">
                  {{ formatTime(msg.created_at) }}
                </div>
              </div>
            </div>
            <div v-if="messages.length === 0" class="h-full flex items-center justify-center text-gray-300 text-[10px] font-bold uppercase tracking-widest">
              Belum ada pesan.
            </div>
          </div>

          <form @submit.prevent="sendMessage" class="p-4 border-t border-black flex gap-4">
            <input 
              v-model="newMessage"
              type="text" 
              placeholder="TULIS PESAN..."
              class="flex-1 bg-white border border-black px-4 py-3 text-sm focus:outline-none focus:border-[#1A56FF] transition-none"
            />
            <button 
              type="submit"
              class="bg-black text-white px-8 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-[#1A56FF] transition-none"
            >
              Kirim
            </button>
          </form>
        </div>
      </div>

      <!-- Right: Status Panel -->
      <div class="space-y-8">
        <div class="bg-white border border-black p-8">
          <h3 class="text-xl font-black uppercase tracking-tighter mb-8 border-b border-black pb-4">Status Panel</h3>
          
          <!-- Timeline -->
          <div class="space-y-6 relative before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-100 mb-12">
            <div 
              v-for="(step, idx) in timeline" 
              :key="step.label"
              :class="['pl-8 relative', step.active ? 'text-black' : 'text-gray-300']"
            >
              <div :class="['absolute left-0 w-4 h-4 border-2 border-white ring-2 ring-current', step.active ? 'bg-[#1A56FF]' : 'bg-gray-200']"></div>
              <div class="text-[10px] font-black uppercase tracking-widest">{{ step.label }}</div>
              <div class="text-[8px] font-bold uppercase">{{ step.time || (step.active ? 'SEDANG BERJALAN' : 'BELUM MULAI') }}</div>
            </div>
          </div>

          <!-- Actions -->
          <div v-if="user?.role === 'freelancer' && booking.status === 'pending'" class="space-y-4">
            <button 
              @click="updateStatus('accepted')"
              class="w-full bg-[#1A56FF] text-white py-4 text-[10px] font-black uppercase tracking-widest border border-black hover:bg-black transition-none"
            >
              Terima Pesanan
            </button>
            <button 
              @click="updateStatus('rejected')"
              class="w-full bg-white text-[#E8192C] py-4 text-[10px] font-black uppercase tracking-widest border border-black hover:bg-[#E8192C] hover:text-white transition-none"
            >
              Tolak Pesanan
            </button>
          </div>

          <button 
            v-if="user?.role === 'freelancer' && booking.status === 'accepted'"
            @click="updateStatus('done')"
            class="w-full bg-black text-white py-4 text-[10px] font-black uppercase tracking-widest border border-black hover:bg-green-600 transition-none"
          >
            Tandai Selesai
          </button>

          <!-- Review Form (Client Only, when status is done) -->
          <div v-if="user?.role === 'client' && booking.status === 'done' && !booking.review" class="mt-8 pt-8 border-t border-black space-y-4">
            <div class="text-[10px] font-black uppercase tracking-widest mb-4">Berikan Ulasan</div>
            <div class="flex gap-2 mb-4">
              <button 
                v-for="star in 5" 
                :key="star"
                @click="reviewForm.rating = star"
                :class="['text-2xl', star <= reviewForm.rating ? 'text-yellow-400' : 'text-gray-200']"
              >
                ★
              </button>
            </div>
            <textarea 
              v-model="reviewForm.comment"
              placeholder="ULASAN ANDA..."
              class="w-full border border-black p-3 text-sm focus:outline-none resize-none"
              rows="3"
            ></textarea>
            <button 
              @click="submitReview"
              class="w-full bg-[#1A56FF] text-white py-3 text-[10px] font-black uppercase tracking-widest hover:bg-black transition-none"
            >
              Kirim Ulasan
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useAuth } from '@/composables/useAuth';

const props = defineProps({
  booking: Object,
  initialMessages: Array
});

const { user, axiosAuth } = useAuth();
const messages = ref(props.initialMessages || []);
const newMessage = ref('');
const chatContainer = ref(null);
const pollingInterval = ref(null);

const reviewForm = ref({
  rating: 5,
  comment: ''
});

const timeline = [
  { label: 'Dipesan', active: true, time: 'HARI INI' },
  { label: 'Diterima', active: ['accepted', 'done'].includes(props.booking.status) },
  { label: 'Selesai', active: props.booking.status === 'done' },
];

const fetchData = async () => {
  try {
    const res = await axiosAuth().get(`/messages/${props.booking.id}`);
    messages.value = res.data;
    nextTick(scrollToBottom);
  } catch (error) {
    console.error('Failed to poll messages');
  }
};

const sendMessage = async () => {
  if (!newMessage.value.trim()) return;
  
  const msgText = newMessage.value;
  newMessage.value = '';
  
  try {
    const res = await axiosAuth().post('/messages', {
      booking_id: props.booking.id,
      message: msgText
    });
    messages.value.push(res.data);
    nextTick(scrollToBottom);
  } catch (error) {
    alert('Gagal mengirim pesan.');
  }
};

const updateStatus = async (status) => {
  if (status === 'rejected' && !confirm('Tolak pesanan ini?')) return;
  
  try {
    await axiosAuth().put(`/bookings/${props.booking.id}`, { status });
    router.reload();
  } catch (error) {
    alert('Gagal mengupdate status.');
  }
};

const submitReview = async () => {
  try {
    await axiosAuth().post('/reviews', {
      booking_id: props.booking.id,
      rating: reviewForm.value.rating,
      comment: reviewForm.value.comment
    });
    router.reload();
  } catch (error) {
    alert('Gagal mengirim ulasan.');
  }
};

const scrollToBottom = () => {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  }
};

onMounted(() => {
  nextTick(scrollToBottom);
  pollingInterval.value = setInterval(fetchData, 5000);
});

onUnmounted(() => {
  if (pollingInterval.value) clearInterval(pollingInterval.value);
});

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);
const formatDate = (dateStr) => new Date(dateStr).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
const formatTime = (dateStr) => new Date(dateStr).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

const statusClass = (status) => {
  switch (status) {
    case 'pending': return 'bg-yellow-400 text-black';
    case 'accepted': return 'bg-[#1A56FF] text-white';
    case 'rejected': return 'bg-[#E8192C] text-white';
    case 'done': return 'bg-black text-white';
    default: return 'bg-gray-200 text-black';
  }
};
</script>

