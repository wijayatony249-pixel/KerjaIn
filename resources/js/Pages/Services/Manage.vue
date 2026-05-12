<template>
  <AppLayout title="Manajemen Layanan">
    <div class="relative z-10">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
          <h1 class="text-4xl font-black uppercase tracking-tighter mb-2 text-white">Layanan Saya</h1>
          <p class="text-white/40 text-xs font-bold uppercase tracking-widest">Kelola jasa yang Anda tawarkan kepada client</p>
        </div>
        <Link 
          href="/layanan/buat"
          class="bg-[#FF3366] text-white font-black px-10 py-4 rounded-lg hover:bg-[#e02d59] transition-all uppercase tracking-widest text-xs shadow-xl shadow-[#FF3366]/20"
        >
          + Buat Layanan Baru
        </Link>
      </div>

      <!-- Filters & Search -->
      <div class="flex flex-col md:flex-row gap-4 mb-10">
        <div class="flex-1 relative">
          <input 
            v-model="search" 
            type="text" 
            placeholder="Cari layanan Anda..." 
            class="w-full bg-white/5 border border-white/10 rounded-md px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all pl-12"
          >
          <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <!-- Services Grid -->
      <div v-if="filteredServices.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <ServiceManageCard 
          v-for="service in filteredServices" 
          :key="service.id" 
          :service="service"
          @delete="deleteService(service.id)"
          @toggle-status="toggleStatus(service)"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="py-32 text-center bg-white/5 border border-white/10 rounded-lg overflow-hidden group hover:border-white/20 transition-all duration-300 backdrop-blur-sm">
        <div class="w-24 h-24 bg-white/5 rounded-md flex items-center justify-center mx-auto mb-8 text-white/10">
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <h2 class="text-3xl font-black uppercase tracking-tighter mb-4 text-white">Belum Ada Layanan</h2>
        <p class="text-white/40 text-sm max-w-sm mx-auto mb-10 leading-relaxed font-medium uppercase tracking-tight">Mulai tawarkan keahlian Anda dan dapatkan penghasilan pertama Anda di KerjaIn.</p>
        <Link href="/layanan/buat" class="bg-[#FF3366] text-white font-black px-12 py-5 rounded-lg hover:bg-[#e02d59] transition-all uppercase tracking-widest text-xs shadow-2xl shadow-[#FF3366]/20">
          Buat Jasa Sekarang
        </Link>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <ConfirmModal 
      :show="showDeleteModal"
      title="Hapus Layanan?"
      message="Data yang dihapus tidak dapat dikembalikan. Seluruh riwayat dan statistik layanan ini akan ikut terhapus dari sistem."
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'
import ServiceManageCard from '../../Components/ServiceManageCard.vue'
import ConfirmModal from '../../Components/ConfirmModal.vue'

const props = defineProps({
  services: Array
})

const search = ref('')
const showDeleteModal = ref(false)
const serviceToDelete = ref(null)

const filteredServices = computed(() => {
  return props.services.filter(s => {
    return s.title.toLowerCase().includes(search.value.toLowerCase())
  })
})

const deleteService = (id) => {
  serviceToDelete.value = id
  showDeleteModal.value = true
}

const executeDelete = () => {
    if (!serviceToDelete.value) return
    router.delete(`/layanan/${serviceToDelete.value}`, {
        onSuccess: () => {
            showDeleteModal.value = false
            serviceToDelete.value = null
        }
    })
}

const toggleStatus = (service) => {
    router.post(`/layanan/${service.id}`, {
        ...service,
        _method: 'POST', // Handled by controller as update
        is_active: !service.is_active
    })
}
</script>
