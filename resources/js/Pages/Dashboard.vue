<template>
  <AppLayout title="Ringkasan Akun">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
      <!-- Stat Cards -->
      <div v-for="stat in stats" :key="stat.label" class="bg-white/[0.03] border border-white/10 p-8 rounded-lg group hover:border-[#FF3366]/50 transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
          <div :class="['w-12 h-12 rounded-md flex items-center justify-center bg-white/5 text-white transition-colors group-hover:bg-[#FF3366] group-hover:shadow-lg group-hover:shadow-[#FF3366]/20']">
            <span v-html="stat.icon" class="w-6 h-6"></span>
          </div>
          <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">Live Data</span>
        </div>
        <div class="text-3xl font-black mb-1">{{ stat.value }}</div>
        <div class="text-[10px] font-black text-white/40 uppercase tracking-widest">{{ stat.label }}</div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Recent Activity -->
      <div class="lg:col-span-2 bg-white/[0.03] border border-white/10 rounded-lg p-8">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-xl font-black uppercase tracking-tighter">Pesanan Terbaru</h2>
          <Link href="/booking" class="text-[10px] font-black text-[#FF3366] hover:underline uppercase tracking-widest">Lihat Semua</Link>
        </div>
        
        <div v-if="recentBookings.length > 0" class="space-y-4">
          <div v-for="booking in recentBookings" :key="booking.id" class="flex items-center justify-between p-4 bg-white/5 rounded-md border border-white/5 hover:border-white/10 transition-colors">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-md bg-[#FF3366]/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-[#FF3366]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
              </div>
              <div>
                <div class="text-sm font-black uppercase tracking-tighter">{{ booking.service?.title }}</div>
                <div class="text-[10px] text-white/40 font-bold uppercase">{{ booking.client?.name }}</div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-sm font-black">Rp {{ formatNumber(booking.service?.price) }}</div>
              <div class="text-[10px] font-black uppercase text-[#FF3366]">{{ booking.status }}</div>
            </div>
          </div>
        </div>
        <div v-else class="py-12 text-center border-2 border-dashed border-white/5 rounded-lg">
          <p class="text-white/20 text-xs font-black uppercase tracking-widest">Belum ada aktivitas pesanan</p>
        </div>
      </div>

      <!-- User Info / Quick Actions -->
      <div class="space-y-6">
        <div class="bg-[#FF3366] rounded-lg p-8 shadow-xl shadow-[#FF3366]/20 border border-white/10">
          <h2 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Saldo Anda</h2>
          <div class="text-4xl font-black text-white mb-8">Rp{{ formatNumber(user?.balance || 0) }}</div>
          <button class="w-full bg-black text-white py-4 rounded-md font-black text-[10px] uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-300">Tarik Saldo Sekarang</button>
        </div>

        <div class="bg-white/[0.03] border border-white/10 rounded-lg p-8">
          <h3 class="text-sm font-black uppercase tracking-tighter mb-6">Tindakan Cepat</h3>
          <div class="grid grid-cols-2 gap-4 text-center">
            <Link href="/layanan/buat" class="p-4 bg-white/5 rounded-md border border-white/5 hover:border-[#FF3366]/40 transition-all group">
              <div class="text-xl mb-2 group-hover:scale-110 transition-transform">✨</div>
              <div class="text-[8px] font-black uppercase tracking-widest text-white/40 group-hover:text-white">Layanan</div>
            </Link>
            <Link href="/profil" class="p-4 bg-white/5 rounded-md border border-white/5 hover:border-[#FF3366]/40 transition-all group">
              <div class="text-xl mb-2 group-hover:scale-110 transition-transform">👤</div>
              <div class="text-[8px] font-black uppercase tracking-widest text-white/40 group-hover:text-white">Profil</div>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { useAuth } from '../composables/useAuth';

const { user, axiosAuth, fetchUser } = useAuth();
const recentBookings = ref([]);
const stats = ref([
  { label: 'Pesanan Aktif', value: '0', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' },
  { label: 'Total Selesai', value: '0', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' },
  { label: 'Review Positif', value: '0', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>' },
  { label: 'Layanan Aktif', value: '0', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' },
]);

const fetchData = async () => {
  try {
    const res = await axiosAuth().get('/me');
    const userData = res.data;
    
    if (userData.role === 'client') {
      stats.value[0].value = userData.active_bookings || 0;
      stats.value[1].value = userData.completed_bookings || 0;
      stats.value[2].value = 'N/A';
      stats.value[3].value = 'N/A';
    } else {
      stats.value[0].value = userData.pending_bookings || 0;
      stats.value[1].value = userData.total_bookings || 0;
      stats.value[2].value = userData.avg_rating || 0;
      stats.value[3].value = userData.total_services || 0;
    }
    
    const bookingsRes = await axiosAuth().get('/bookings');
    recentBookings.value = bookingsRes.data.slice(0, 5);
  } catch (error) {
    console.error('Failed to fetch dashboard data');
  }
};

onMounted(async () => {
  if (!user.value) await fetchUser();
  fetchData();
});

const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num || 0);
};
</script>
