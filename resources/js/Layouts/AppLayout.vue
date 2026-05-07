<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white flex font-sans" style="font-family: 'Inter', sans-serif;">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0a0a0a] border-r border-white/5 flex flex-col fixed h-full z-20">
      <div class="p-8">
        <div class="text-2xl font-black tracking-tighter mb-10">
          <span class="text-white">KERJA</span><span class="text-[#FF3366]">//</span><span class="text-white">IN</span>
        </div>

        <nav class="space-y-2">
          <Link
            v-for="item in menuItems"
            :key="item.label"
            :href="item.href"
            :class="[
              'flex items-center gap-4 px-4 py-3 rounded-md text-xs font-bold uppercase tracking-widest transition-all duration-200 group',
              $page.url === item.href ? 'bg-[#FF3366] text-white shadow-lg shadow-[#FF3366]/20' : 'text-white/40 hover:text-white hover:bg-white/5'
            ]"
          >
            <span v-html="item.icon" class="w-5 h-5 transition-transform group-hover:scale-110"></span>
            {{ item.label }}
          </Link>
        </nav>
      </div>

      <div class="mt-auto p-8 border-t border-white/5">
        <div class="flex items-center gap-3 mb-6 p-2 rounded-lg bg-white/5 border border-white/10">
          <div class="w-10 h-10 rounded-md bg-gradient-to-br from-[#FF3366] to-[#6C63FF] flex items-center justify-center font-black text-sm overflow-hidden">
            <img v-if="user?.avatar" :src="'/storage/' + user.avatar" class="w-full h-full object-cover" />
            <span v-else>{{ user?.name?.[0] || '?' }}</span>
          </div>
          <div class="overflow-hidden">
            <div class="text-xs font-black truncate">{{ user?.name }}</div>
            <div class="text-[10px] text-white/40 font-bold uppercase tracking-tighter">{{ user?.role }}</div>
          </div>
        </div>
        
        <button
          @click="handleLogout"
          class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-[10px] font-black uppercase tracking-widest text-[#FF3366] hover:bg-[#FF3366]/10 transition-all duration-200"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Keluar
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8 relative min-h-screen overflow-hidden">
      <!-- Background Aurora Glows -->
      <div class="fixed top-0 left-64 w-full h-full pointer-events-none z-0 overflow-hidden bg-[#0a0a0a]">
        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.03] mix-blend-overlay"></div>
        
        <!-- Blobs -->
        <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] bg-[#FF3366]/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[10%] -right-[20%] w-[60%] h-[60%] bg-[#6C63FF]/15 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[0%] left-[10%] w-[80%] h-[50%] bg-[#FF3366]/10 rounded-full blur-[150px]"></div>
      </div>
      
      <header class="flex items-center justify-between mb-12 relative z-10">
        <h1 class="text-4xl font-black uppercase tracking-tighter">{{ title }}</h1>
        
        <div class="flex items-center gap-6">
          <div class="flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2 rounded-full">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-white/60">Sistem Online</span>
          </div>
        </div>
      </header>

      <div class="relative z-10">
        <slot />
      </div>
    </main>

    <!-- Global Components -->
    <ToastList />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { useAuth } from '../composables/useAuth';
import ToastList from '../Components/ToastList.vue';

const props = defineProps({
  title: String
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const menuItems = [
  { label: 'Beranda', href: '/dashboard', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>' },
  { label: 'Layanan', href: '/layanan', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' },
  { label: 'Pesanan', href: '/booking', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"/></svg>' },
  { label: 'Profil', href: '/profil', icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' },
];

const handleLogout = () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    router.post('/logout', {}, {
      onFinish: () => {
        localStorage.removeItem('kerjain_token');
        window.location.href = '/';
      }
    });
  }
};
</script>
