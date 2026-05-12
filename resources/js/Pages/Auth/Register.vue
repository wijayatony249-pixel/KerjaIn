<template>
  <div class="min-h-screen bg-[#0a0a0a] flex items-center justify-center p-6 relative overflow-hidden font-sans" style="font-family: 'Inter', sans-serif;">
    <!-- Background Effects -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
      <div class="absolute top-1/4 -right-32 w-96 h-96 bg-[#FF3366]/10 rounded-full blur-[120px]"></div>
      <div class="absolute bottom-1/4 -left-32 w-96 h-96 bg-[#6C63FF]/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-2xl relative z-10">
      <div class="text-center mb-10">
        <div class="text-3xl font-black tracking-tighter mb-2">
          <span class="text-white">KERJA</span><span class="text-[#FF3366]">//</span><span class="text-white">IN</span>
        </div>
        <p class="text-white/40 text-[10px] font-bold uppercase tracking-[0.3em]">Gabung dengan Ekosistem</p>
      </div>

      <div class="bg-white/[0.03] border border-white/10 backdrop-blur-xl p-12 rounded-[24px] shadow-2xl relative overflow-hidden">
        <h1 class="text-4xl font-black text-white mb-10 uppercase tracking-tighter text-center">Buat Akun</h1>
        
        <form @submit.prevent="handleRegister" class="space-y-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="md:col-span-2">
              <label class="block text-white/40 text-[10px] font-bold mb-4 uppercase tracking-[0.2em]">Pilih Peran Anda</label>
              <div class="grid grid-cols-2 gap-4">
                <button 
                  type="button"
                  @click="form.role = 'client'"
                  :class="['py-4 rounded-lg text-xs font-black uppercase tracking-widest transition-all duration-300 border', form.role === 'client' ? 'bg-white text-black border-white' : 'bg-white/5 text-white/40 border-white/10 hover:bg-white/10']"
                >
                  Client
                </button>
                <button 
                  type="button"
                  @click="form.role = 'freelancer'"
                  :class="['py-4 rounded-lg text-xs font-black uppercase tracking-widest transition-all duration-300 border', form.role === 'freelancer' ? 'bg-[#FF3366] text-white border-[#FF3366]' : 'bg-white/5 text-white/40 border-white/10 hover:bg-white/10']"
                >
                  Freelancer
                </button>
              </div>
            </div>

            <div class="md:col-span-2">
              <label class="block text-white/40 text-[10px] font-bold mb-3 uppercase tracking-[0.2em]">Nama Lengkap</label>
              <input
                v-model="form.name"
                type="text"
                placeholder="John Doe"
                class="w-full bg-white/5 border border-white/10 rounded-lg px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
                required
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-white/40 text-[10px] font-bold mb-3 uppercase tracking-[0.2em]">Email Address</label>
              <input
                v-model="form.email"
                type="email"
                placeholder="john@example.com"
                class="w-full bg-white/5 border border-white/10 rounded-lg px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
                required
              />
            </div>

            <div>
              <label class="block text-white/40 text-[10px] font-bold mb-3 uppercase tracking-[0.2em]">Password</label>
              <input
                v-model="form.password"
                type="password"
                placeholder="••••••••"
                class="w-full bg-white/5 border border-white/10 rounded-lg px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
                required
              />
            </div>

            <div>
              <label class="block text-white/40 text-[10px] font-bold mb-3 uppercase tracking-[0.2em]">Konfirmasi</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="••••••••"
                class="w-full bg-white/5 border border-white/10 rounded-lg px-6 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
                required
              />
            </div>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-[#FF3366] text-white py-5 rounded-lg font-black text-sm hover:bg-[#e02d59] shadow-xl shadow-[#FF3366]/20 transition-all duration-300 disabled:opacity-50 uppercase tracking-widest"
          >
            {{ form.processing ? 'Mendaftarkan...' : 'Buat Akun Sekarang' }}
          </button>
        </form>

        <div class="mt-12 pt-8 border-t border-white/5 text-center">
          <p class="text-white/30 text-[10px] uppercase tracking-widest font-bold">
            Sudah punya akun?
            <Link href="/login" class="text-[#FF3366] font-black hover:text-white transition-colors ml-2">Masuk Saja</Link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'client',
});

const handleRegister = () => {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
