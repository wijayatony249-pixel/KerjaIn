<template>
  <AppLayout title="Dashboard">
    <!-- Dashboard Background Glows -->
    <div class="fixed inset-0 pointer-events-none z-0">
      <div class="absolute top-1/4 -left-20 w-96 h-96 bg-[#FF3366]/10 rounded-full blur-[120px]"></div>
      <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-[#1A56FF]/10 rounded-full blur-[120px]"></div>
    </div>

    <div v-if="isFreelancer" class="space-y-12 relative z-10">
      <!-- Freelancer Stats -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard label="Total Layanan" :value="stats.services" accentColor="blue" />
        <StatCard label="Booking Masuk" :value="stats.pendingBookings" accentColor="red" />
        <StatCard label="Aktif" :value="stats.activeBookings" accentColor="black" />
        <StatCard label="Tuntas" :value="stats.doneBookings" accentColor="gray" />
      </div>

      <!-- Quick Actions -->
      <div class="flex flex-wrap gap-4">
        <Link href="/layanan/buat" class="bg-[#FF3366] text-white font-black px-8 py-4 rounded-md hover:bg-[#e02d59] transition-all uppercase tracking-widest text-xs shadow-xl shadow-[#FF3366]/20">
          + Tambah Layanan
        </Link>
        <Link href="/booking" class="bg-white/5 border border-white/10 text-white font-black px-8 py-4 rounded-md hover:bg-white/10 transition-all uppercase tracking-widest text-xs">
          Manajemen Booking
        </Link>
      </div>

      <!-- Incoming Bookings -->
      <div class="space-y-6">
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-black uppercase tracking-tighter">Booking Masuk</h2>
          <Link href="/booking" class="text-[#FF3366] text-xs font-bold uppercase tracking-widest hover:underline">Semua Pesanan →</Link>
        </div>
        
        <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden backdrop-blur-xl">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="text-[10px] font-black uppercase tracking-[0.3em] text-white/30 border-b border-white/5 bg-white/[0.02]">
                <th class="p-6">Layanan</th>
                <th class="p-6">Client</th>
                <th class="p-6">Tanggal</th>
                <th class="p-6">Status</th>
                <th class="p-6">Aksi</th>
              </tr>
            </thead>
            <tbody class="text-sm">
              <tr v-for="booking in pendingBookings" :key="booking.id" class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                <td class="p-6">
                    <div class="font-black text-white uppercase tracking-tight">{{ booking.service?.title }}</div>
                    <div class="text-[10px] text-white/40 font-bold uppercase tracking-widest">{{ booking.service?.category }}</div>
                </td>
                <td class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-md bg-gradient-to-br from-[#FF3366] to-[#6C63FF] text-[10px] font-black flex items-center justify-center text-white">{{ booking.client?.name?.[0] }}</div>
                        <span class="font-bold text-white/80">{{ booking.client?.name }}</span>
                    </div>
                </td>
                <td class="p-6 font-bold text-white/60">{{ formatDate(booking.booking_date) }}</td>
                <td class="p-6"><StatusBadge :status="booking.status" /></td>
                <td class="p-6">
                    <div class="flex gap-2">
                        <button @click="updateBookingStatus(booking.id, 'accepted')" class="bg-[#1A56FF] text-white px-4 py-2 rounded-md text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all">Terima</button>
                        <button @click="updateBookingStatus(booking.id, 'rejected')" class="bg-white/5 text-white/60 px-4 py-2 rounded-md text-[10px] font-black uppercase tracking-widest hover:bg-[#FF3366] hover:text-white transition-all">Tolak</button>
                    </div>
                </td>
              </tr>
              <tr v-if="pendingBookings.length === 0">
                <td colspan="5" class="p-20 text-center text-white/20 font-black uppercase tracking-[0.2em] italic text-xs">Belum ada booking masuk baru</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Client Dashboard -->
    <div v-else class="space-y-12 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard label="Total Pesanan" :value="stats.totalBookings" accentColor="blue" />
        <StatCard label="Menunggu" :value="stats.pendingBookings" accentColor="red" />
        <StatCard label="Berjalan" :value="stats.activeBookings" accentColor="black" />
        <StatCard label="Tuntas" :value="stats.doneBookings" accentColor="gray" />
      </div>

      <div class="space-y-6">
        <h2 class="text-2xl font-black uppercase tracking-tighter">Pesanan Terbaru</h2>
        <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden backdrop-blur-xl">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="text-[10px] font-black uppercase tracking-[0.3em] text-white/30 border-b border-white/5 bg-white/[0.02]">
                <th class="p-6">Layanan</th>
                <th class="p-6">Freelancer</th>
                <th class="p-6">Tanggal</th>
                <th class="p-6">Status</th>
                <th class="p-6 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="text-sm">
              <tr v-for="booking in recentBookings" :key="booking.id" class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                <td class="p-6 font-black text-white uppercase tracking-tight">{{ booking.service?.title }}</td>
                <td class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-md bg-white/10 border border-white/5 text-[10px] font-black flex items-center justify-center">{{ booking.freelancer?.name?.[0] }}</div>
                        <span class="font-bold text-white/80">{{ booking.freelancer?.name }}</span>
                    </div>
                </td>
                <td class="p-6 font-bold text-white/60">{{ formatDate(booking.booking_date) }}</td>
                <td class="p-6"><StatusBadge :status="booking.status" /></td>
                <td class="p-6 text-right">
                  <Link :href="`/booking/${booking.id}`" class="bg-white/5 hover:bg-white/10 text-white px-6 py-2 rounded-md text-[10px] font-black uppercase tracking-widest border border-white/5 transition-all">Detail</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '../Layouts/AppLayout.vue'
import StatCard from '../Components/StatCard.vue'
import StatusBadge from '../Components/StatusBadge.vue'
import { useAuth } from '../composables/useAuth'
import { useApi } from '../composables/useApi'

const { isFreelancer } = useAuth()
const { get, put } = useApi()

const bookings = ref([])
const services = ref([])
const stats = ref({ services: 0, totalBookings: 0, pendingBookings: 0, activeBookings: 0, doneBookings: 0 })

const fetchData = async () => {
  try {
    const [bookingsRes, servicesRes] = await Promise.all([
      get('/bookings?all=true'),
      isFreelancer.value ? get('/services?my=true&all=true') : Promise.resolve({ data: { data: [] } })
    ])
    bookings.value = bookingsRes.data.data
    services.value = servicesRes.data.data
    stats.value = {
      services: services.value.length,
      totalBookings: bookings.value.length,
      pendingBookings: bookings.value.filter(b => b.status === 'pending').length,
      activeBookings: bookings.value.filter(b => b.status === 'accepted').length,
      doneBookings: bookings.value.filter(b => b.status === 'done').length
    }
  } catch (error) { console.error(error) }
}

const pendingBookings = computed(() => bookings.value.filter(b => b.status === 'pending'))
const recentBookings = computed(() => bookings.value.slice(0, 5))

const updateBookingStatus = async (id, status) => {
  if (!confirm('Update status pesanan?')) return
  try {
    await put(`/bookings/${id}`, { status })
    fetchData()
  } catch (error) { alert('Gagal update status') }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

onMounted(fetchData)
</script>
