<template>
  <div class="min-h-screen bg-white font-sans" style="font-family: 'Inter', sans-serif;">
    <!-- Navigation (Public) -->
    <nav class="h-20 bg-white border-b border-black flex items-center justify-between px-8 sticky top-0 z-50">
      <Link href="/" class="text-2xl font-black tracking-tighter">
        <span class="text-black">KERJA</span><span class="text-[#1A56FF]">//</span><span class="text-black">IN</span>
      </Link>
      <div class="flex items-center gap-6">
        <Link v-if="!user" href="/login" class="text-[10px] font-black uppercase tracking-widest text-black hover:text-[#1A56FF]">Masuk</Link>
        <Link v-if="!user" href="/register" class="bg-black text-white px-6 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-[#1A56FF] transition-none">Daftar</Link>
        <Link v-else href="/dashboard" class="text-[10px] font-black uppercase tracking-widest text-black hover:text-[#1A56FF]">Dashboard</Link>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-8 py-12">
      <div class="flex flex-col lg:flex-row gap-12">
        <!-- Left Content -->
        <div class="flex-1">
          <div class="aspect-video bg-gray-100 border border-black mb-8 overflow-hidden relative">
            <div class="absolute inset-0 flex items-center justify-center text-gray-200 font-black uppercase tracking-tighter text-6xl">
              KERJA//IN
            </div>
            <img v-if="service.thumbnail" :src="service.thumbnail" class="w-full h-full object-cover relative z-10" />
          </div>

          <div class="flex items-center gap-3 mb-4">
            <span class="bg-[#1A56FF] text-white px-3 py-1 text-[10px] font-black uppercase tracking-widest">{{ service.category }}</span>
          </div>

          <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter mb-8 leading-none">{{ service.title }}</h1>

          <div class="prose max-w-none mb-12">
            <h3 class="text-xl font-black uppercase tracking-tighter mb-4 border-b-2 border-black inline-block">Deskripsi</h3>
            <p class="text-gray-600 leading-relaxed whitespace-pre-line text-lg">
              {{ service.description }}
            </p>
          </div>

          <!-- Freelancer Card -->
          <div class="border border-black p-8 flex flex-col md:flex-row items-center gap-8">
            <div class="w-24 h-24 rounded-full bg-[#1A56FF] flex items-center justify-center text-white text-3xl font-black">
              {{ service.freelancer?.name?.[0] || '?' }}
            </div>
            <div class="flex-1 text-center md:text-left">
              <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">FREELANCER</div>
              <h4 class="text-2xl font-black uppercase tracking-tighter mb-2">{{ service.freelancer?.name }}</h4>
              <p class="text-gray-500 text-sm mb-4">{{ service.freelancer?.bio || 'Freelancer profesional di KerjaIn.' }}</p>
              <div class="flex items-center justify-center md:justify-start gap-4">
                <span class="text-sm font-black">★ {{ service.freelancer?.rating || '0.0' }}</span>
                <span class="text-gray-300">|</span>
                <span class="text-xs font-bold uppercase tracking-widest">{{ service.freelancer?.completed_projects || 0 }} PROJECT SELESAI</span>
              </div>
            </div>
            <Link :href="`/profil/${service.freelancer_id}`" class="border border-black px-8 py-4 text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-none">
              Lihat Profil
            </Link>
          </div>
        </div>

        <!-- Right Sticky Panel -->
        <div class="w-full lg:w-[400px]">
          <div class="sticky top-32 border-4 border-black p-8 bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
            <div class="mb-8 border-b border-black pb-6">
              <div class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">HARGA LAYANAN</div>
              <div class="text-5xl font-black text-[#1A56FF] tracking-tighter">Rp {{ formatNumber(service.price) }}</div>
            </div>

            <form @submit.prevent="handleBooking" class="space-y-6">
              <div>
                <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Pilih Tanggal</label>
                <input
                  v-model="form.booking_date"
                  type="date"
                  class="w-full bg-white border border-black px-4 py-3 text-black text-sm focus:outline-none focus:border-[#1A56FF] transition-none"
                  required
                />
              </div>

              <div>
                <label class="block text-black text-xs font-bold mb-2 uppercase tracking-widest">Catatan</label>
                <textarea
                  v-model="form.note"
                  rows="4"
                  placeholder="BERIKAN DETAIL KEBUTUHAN ANDA..."
                  class="w-full bg-white border border-black px-4 py-3 text-black text-sm focus:outline-none focus:border-[#1A56FF] transition-none resize-none"
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-[#1A56FF] text-white py-5 text-xs font-black uppercase tracking-widest hover:bg-black transition-none disabled:bg-gray-400"
              >
                {{ form.processing ? 'Memproses...' : 'Pesan Sekarang' }}
              </button>
            </form>

            <div class="mt-8 text-center">
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                PEMBAYARAN AKAN DILAKUKAN<br/>SETELAH FREELANCER MENYETUJUI.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useAuth } from '@/composables/useAuth';

const props = defineProps({
  service: Object
});

const { user } = useAuth();
const page = usePage();

const form = useForm({
  service_id: props.service.id,
  booking_date: '',
  note: ''
});

const handleBooking = () => {
  if (!user.value) {
    alert('Silakan login terlebih dahulu untuk melakukan booking.');
    window.location.href = '/login';
    return;
  }

  form.post('/api/bookings', {
    onSuccess: (response) => {
      // Logic handled by controller or redirect
    }
  });
};

const formatNumber = (num) => {
  return new Intl.NumberFormat('id-ID').format(num);
};
</script>

