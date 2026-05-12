<template>
  <AppLayout title="Daftar Pesanan">
    <div class="relative z-10">
      <BackButton href="/dashboard" label="Kembali ke Dashboard" />
      <!-- Tab Bar -->
      <div class="flex gap-4 border-b border-white/5 mb-10 overflow-x-auto pb-4">
        <button 
          v-for="tab in tabs" 
          :key="tab"
          @click="activeTab = tab"
          :class="[
            'px-6 py-2 text-xs font-black uppercase tracking-[0.2em] transition-all whitespace-nowrap rounded-md border',
            activeTab === tab ? 'bg-[#FF3366] text-white border-[#FF3366] shadow-lg shadow-[#FF3366]/20' : 'text-white/40 hover:text-white border-transparent'
          ]"
        >
          {{ tab }}
        </button>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <StatCard label="Total Booking" :value="stats.total" accentColor="blue" />
        <StatCard label="Menunggu" :value="stats.pending" accentColor="red" />
        <StatCard label="Aktif" :value="stats.accepted" accentColor="black" />
        <StatCard label="Tuntas" :value="stats.done" accentColor="gray" />
      </div>

      <!-- Table Container -->
      <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden backdrop-blur-xl">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-[10px] font-black uppercase tracking-[0.3em] text-white/30 border-b border-white/5 bg-white/[0.02]">
              <th class="p-6">No</th>
              <th class="p-6">Layanan</th>
              <th class="p-6">{{ isFreelancer ? 'Client' : 'Freelancer' }}</th>
              <th class="p-6">Tanggal</th>
              <th class="p-6">Status</th>
              <th class="p-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-sm">
            <tr v-for="(booking, index) in filteredBookings" :key="booking.id" class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
              <td class="p-6 text-white/20 font-black">{{ index + 1 }}</td>
              <td class="p-6">
                <div class="font-black text-white uppercase tracking-tight">{{ booking.service?.title }}</div>
                <div class="text-[10px] text-white/40 font-bold uppercase tracking-widest">{{ booking.service?.category }}</div>
              </td>
              <td class="p-6">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-md bg-gradient-to-br from-[#FF3366] to-[#6C63FF] text-[10px] font-black flex items-center justify-center text-white">
                    {{ (isFreelancer ? booking.client?.name : booking.freelancer?.name)?.[0] }}
                  </div>
                  <div class="font-bold text-white/80">{{ isFreelancer ? booking.client?.name : booking.freelancer?.name }}</div>
                </div>
              </td>
              <td class="p-6 font-bold text-white/60">{{ formatDate(booking.booking_date) }}</td>
              <td class="p-6">
                <StatusBadge :status="booking.status" />
              </td>
              <td class="p-6 text-right">
                <div class="flex justify-end gap-3">
                  <Link :href="`/booking/${booking.id}`" class="bg-white/5 hover:bg-white/10 text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/5 transition-all">
                    Detail
                  </Link>
                  
                  <template v-if="isFreelancer && booking.status === 'pending'">
                    <button @click="updateStatus(booking.id, 'accepted')" class="bg-[#1A56FF] text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-blue-500/20">
                      Terima
                    </button>
                    <button @click="updateStatus(booking.id, 'rejected')" class="bg-[#FF3366]/10 text-[#FF3366] px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#FF3366] hover:text-white transition-all">
                      Tolak
                    </button>
                  </template>
                </div>
              </td>
            </tr>
            <tr v-if="filteredBookings.length === 0">
              <td colspan="6" class="p-32 text-center text-white/20 font-black uppercase tracking-[0.4em] italic text-xs">
                Belum ada riwayat pesanan di kategori ini
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="flex justify-center gap-3 mt-12">
        <button 
          @click="fetchData(pagination.current_page - 1)" 
          :disabled="pagination.current_page === 1"
          class="bg-white/5 border border-white/10 rounded-lg px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white disabled:opacity-20 hover:bg-white/10 transition-all"
        >
          Prev
        </button>
        <button 
          v-for="page in pagination.last_page" 
          :key="page"
          @click="fetchData(page)"
          :class="[
            'w-10 h-10 flex items-center justify-center rounded-lg text-[10px] font-black transition-all border',
            pagination.current_page === page ? 'bg-[#FF3366] text-white border-[#FF3366] shadow-lg shadow-[#FF3366]/20' : 'bg-white/5 text-white/40 border-white/10 hover:border-white/30'
          ]"
        >
          {{ page }}
        </button>
        <button 
          @click="fetchData(pagination.current_page + 1)" 
          :disabled="pagination.current_page === pagination.last_page"
          class="bg-white/5 border border-white/10 rounded-lg px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white disabled:opacity-20 hover:bg-white/10 transition-all"
        >
          Next
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import StatCard from '../../Components/StatCard.vue'
import StatusBadge from '../../Components/StatusBadge.vue'
import BackButton from '../../Components/BackButton.vue'
import { useAuth } from '../../composables/useAuth'
import { useApi } from '../../composables/useApi'

const { isFreelancer } = useAuth()
const { get, put } = useApi()

const activeTab = ref('Semua')
const tabs = ['Semua', 'Pending', 'Diterima', 'Selesai', 'Ditolak']

const bookings = ref([])
const pagination = ref({ current_page: 1, last_page: 1 })
const stats = ref({ total: 0, pending: 0, accepted: 0, done: 0 })

const fetchData = async (page = 1) => {
  try {
    const res = await get(`/bookings?page=${page}`)
    bookings.value = res.data.data
    pagination.value = {
        current_page: res.data.current_page,
        last_page: res.data.last_page
    }
    updateStats()
  } catch (error) {
    console.error('Failed to fetch bookings', error)
  }
}

const updateStats = () => {
    stats.value = {
        total: bookings.value.length,
        pending: bookings.value.filter(b => b.status === 'pending').length,
        accepted: bookings.value.filter(b => b.status === 'accepted').length,
        done: bookings.value.filter(b => b.status === 'done').length
    }
}

const filteredBookings = computed(() => {
  if (activeTab.value === 'Semua') return bookings.value
  const statusMap = {
    'Pending': 'pending',
    'Diterima': 'accepted',
    'Selesai': 'done',
    'Ditolak': 'rejected'
  }
  return bookings.value.filter(b => b.status === statusMap[activeTab.value])
})

const updateStatus = async (id, status) => {
  if (!confirm(`Update status pesanan ini?`)) return
  try {
    await put(`/bookings/${id}`, { status })
    fetchData(pagination.value.current_page)
  } catch (error) {
    alert('Gagal memperbarui status')
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

onMounted(fetchData)
</script>
