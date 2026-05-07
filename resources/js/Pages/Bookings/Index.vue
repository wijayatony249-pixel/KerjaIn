<template>
  <AppLayout title="Riwayat Pesanan">
    <!-- Tabs -->
    <div class="flex items-center gap-2 bg-white/5 p-1 rounded-lg mb-10 w-fit border border-white/5">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        @click="activeTab = tab.value"
        :class="[
          'px-6 py-3 text-[10px] font-black uppercase tracking-widest rounded-md transition-all duration-300',
          activeTab === tab.value
            ? 'bg-[#FF3366] text-white shadow-lg shadow-[#FF3366]/20'
            : 'text-white/40 hover:text-white'
        ]"
      >
        {{ tab.label }}
      </button>
    </div>

    <!-- Booking List (Card Style) -->
    <div class="grid grid-cols-1 gap-6">
      <div 
        v-for="booking in filteredBookings" 
        :key="booking.id" 
        class="bg-white/[0.03] border border-white/5 p-6 rounded-lg flex flex-col md:flex-row items-center justify-between gap-6 hover:border-[#FF3366]/30 transition-all group"
      >
        <div class="flex items-center gap-6 w-full md:w-auto">
          <!-- Service Thumbnail/Icon -->
          <div class="w-16 h-16 rounded-lg bg-white/5 flex items-center justify-center text-[#FF3366] group-hover:bg-[#FF3366] group-hover:text-white transition-all duration-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
          </div>

          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 mb-1">
              <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">#ID-{{ booking.id }}</span>
              <div :class="['w-2 h-2 rounded-full', statusPulse(booking.status)]"></div>
            </div>
            <h3 class="text-lg font-black uppercase tracking-tighter truncate">{{ booking.service?.title }}</h3>
            <div class="flex items-center gap-2 mt-1">
              <div class="w-4 h-4 rounded bg-white/10 flex items-center justify-center text-[8px] font-black">
                {{ (user?.role === 'freelancer' ? booking.client?.name : booking.freelancer?.name)?.[0] }}
              </div>
              <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">
                {{ user?.role === 'freelancer' ? booking.client?.name : booking.freelancer?.name }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap items-center justify-between md:justify-end gap-8 w-full md:w-auto border-t md:border-t-0 border-white/5 pt-4 md:pt-0">
          <div class="text-center md:text-right">
            <div class="text-white/20 text-[8px] font-black uppercase tracking-widest mb-1">TANGGAL</div>
            <div class="text-xs font-black text-white/80 uppercase">{{ formatDate(booking.booking_date) }}</div>
          </div>

          <div class="text-center md:text-right">
            <div class="text-white/20 text-[8px] font-black uppercase tracking-widest mb-1">STATUS</div>
            <span :class="['text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter border', statusClass(booking.status)]">
              {{ booking.status }}
            </span>
          </div>

          <div class="text-center md:text-right">
            <div class="text-white/20 text-[8px] font-black uppercase tracking-widest mb-1">TOTAL</div>
            <div class="text-lg font-black text-white">Rp{{ formatNumber(booking.service?.price) }}</div>
          </div>

          <Link :href="`/booking/${booking.id}`" class="px-8 py-3 bg-white text-black rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#FF3366] hover:text-white transition-all shadow-lg">
            Detail
          </Link>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredBookings.length === 0" class="py-32 bg-white/[0.02] border-2 border-dashed border-white/5 rounded-3xl flex flex-col items-center justify-center text-center">
        <div class="text-6xl text-white/5 font-black mb-6 uppercase tracking-tighter animate-pulse">KERJA//IN</div>
        <h3 class="text-2xl font-black uppercase tracking-tighter mb-2">Belum ada pesanan</h3>
        <p class="text-white/20 text-[10px] font-black uppercase tracking-widest">Aktivitas pesanan Anda akan muncul di sini.</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import { useAuth } from '../../composables/useAuth';

const props = defineProps({
  bookings: Array
});

const { user } = useAuth();
const activeTab = ref('all');

const tabs = [
  { label: 'Semua', value: 'all' },
  { label: 'Pending', value: 'pending' },
  { label: 'Proses', value: 'accepted' },
  { label: 'Selesai', value: 'done' },
  { label: 'Batal', value: 'rejected' },
];

const filteredBookings = computed(() => {
  if (activeTab.value === 'all') return props.bookings;
  return props.bookings.filter(b => b.status === activeTab.value);
});

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  });
};

const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num || 0);
};

const statusClass = (status) => {
  switch (status) {
    case 'pending': return 'bg-yellow-400/10 text-yellow-400 border-yellow-400/20';
    case 'accepted': return 'bg-[#FF3366]/10 text-[#FF3366] border-[#FF3366]/20';
    case 'rejected': return 'bg-red-500/10 text-red-500 border-red-500/20';
    case 'done': return 'bg-green-500/10 text-green-500 border-green-500/20';
    default: return 'bg-white/10 text-white border-white/20';
  }
};

const statusPulse = (status) => {
  switch (status) {
    case 'pending': return 'bg-yellow-400 animate-pulse';
    case 'accepted': return 'bg-[#FF3366] animate-pulse';
    case 'rejected': return 'bg-red-500';
    case 'done': return 'bg-green-500';
    default: return 'bg-white';
  }
};
</script>
