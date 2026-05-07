<template>
  <div class="min-h-screen bg-[#0a0a0a] flex items-center justify-center p-6 relative overflow-hidden" style="font-family: 'Inter', sans-serif;">
    <!-- Background Effects -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
      <div class="absolute top-1/4 -right-32 w-96 h-96 bg-[#FF3366]/10 rounded-full blur-[120px]"></div>
      <div class="absolute bottom-1/4 -left-32 w-96 h-96 bg-[#6C63FF]/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-lg relative z-10">
      <div class="text-center mb-10">
        <div class="text-3xl font-black tracking-tighter mb-2">
          <span class="text-white">KERJA</span><span class="text-[#FF3366]">//</span><span class="text-white">IN</span>
        </div>
        <p class="text-white/40 text-xs font-bold uppercase tracking-[0.2em]">Bergabung dengan Kreator Terbaik</p>
      </div>

      <div class="bg-white/[0.03] border border-white/10 backdrop-blur-xl p-10 rounded-2xl shadow-2xl">
        <h1 class="text-3xl font-black text-white mb-8 uppercase tracking-tighter text-center">Daftar</h1>
        
        <form @submit.prevent="handleRegister" class="space-y-6">
          <!-- Role Selector -->
          <div class="grid grid-cols-2 bg-white/5 rounded-xl p-1 border border-white/10">
            <button
              type="button"
              @click="form.role = 'client'"
              :class="[
                'py-3 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all duration-300',
                form.role === 'client' ? 'bg-[#FF3366] text-white shadow-lg' : 'text-white/40 hover:text-white'
              ]"
            >
              Klien
            </button>
            <button
              type="button"
              @click="form.role = 'freelancer'"
              :class="[
                'py-3 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all duration-300',
                form.role === 'freelancer' ? 'bg-[#FF3366] text-white shadow-lg' : 'text-white/40 hover:text-white'
              ]"
            >
              Freelancer
            </button>
          </div>

          <div>
            <label class="block text-white/60 text-[10px] font-bold mb-2 uppercase tracking-widest">Nama Lengkap</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Nama Lengkap Anda"
              class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
              required
            />
            <p v-if="form.errors.name" class="text-[#FF3366] text-[10px] mt-2 uppercase font-bold">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-white/60 text-[10px] font-bold mb-2 uppercase tracking-widest">Email Address</label>
            <input
              v-model="form.email"
              type="email"
              placeholder="nama@email.com"
              class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
              required
            />
            <p v-if="form.errors.email" class="text-[#FF3366] text-[10px] mt-2 uppercase font-bold">{{ form.errors.email }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/60 text-[10px] font-bold mb-2 uppercase tracking-widest">Password</label>
              <input
                v-model="form.password"
                type="password"
                placeholder="••••••••"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
                required
              />
            </div>
            <div>
              <label class="block text-white/60 text-[10px] font-bold mb-2 uppercase tracking-widest">Konfirmasi</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="••••••••"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white text-sm focus:outline-none focus:border-[#FF3366]/50 transition-all duration-300"
                required
              />
            </div>
          </div>
          <p v-if="form.errors.password" class="text-[#FF3366] text-[10px] -mt-4 uppercase font-bold">{{ form.errors.password }}</p>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-[#FF3366] text-white py-4 rounded-xl font-black text-sm hover:bg-[#e02d59] shadow-lg shadow-[#FF3366]/20 transition-all duration-300 disabled:opacity-50 uppercase tracking-widest"
          >
            {{ form.processing ? 'Memproses...' : 'Daftar Sekarang' }}
          </button>
        </form>

        <div class="mt-10 pt-8 border-t border-white/5 text-center">
          <p class="text-white/30 text-[10px] uppercase tracking-widest">
            Sudah punya akun?
            <Link href="/login" class="text-[#FF3366] font-black hover:text-white transition-colors ml-2">Masuk di sini</Link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { useToast } from '../../composables/useToast';

const { addToast } = useToast();
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'client',
});

const handleRegister = () => {
  form.post('/register', {
    onSuccess: () => {
      addToast('Registrasi Berhasil! Selamat datang di KerjaIn.');
    },
    onError: (errors) => {
      addToast('Gagal registrasi. Periksa kembali data Anda.', 'error');
    },
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
