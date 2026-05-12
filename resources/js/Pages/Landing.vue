<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white overflow-x-hidden font-sans" style="font-family: 'Inter', sans-serif;">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 px-8 py-5 flex items-center justify-between backdrop-blur-md bg-[#0a0a0a]/80 border-b border-white/5">
      <div class="text-2xl font-black tracking-tighter">
        <span class="text-white">KERJA</span><span class="text-[#FF3366]">//</span><span class="text-white">IN</span>
      </div>
      <div class="hidden md:flex gap-8 items-center">
        <a href="#services" class="text-white/60 hover:text-white text-sm font-medium transition-colors duration-200">Jelajahi</a>
        <a href="#categories" class="text-white/60 hover:text-white text-sm font-medium transition-colors duration-200">Kategori</a>
        <a href="#how" class="text-white/60 hover:text-white text-sm font-medium transition-colors duration-200">Cara Kerja</a>
      </div>
      <div class="flex gap-3">
        <template v-if="auth.user">
          <Link href="/dashboard" class="px-5 py-2 text-sm font-semibold bg-[#FF3366] text-white rounded-md hover:bg-[#e02d59] transition-all duration-200 shadow-lg shadow-[#FF3366]/20">
            Dashboard
          </Link>
        </template>
        <template v-else>
          <Link href="/login" class="px-5 py-2 text-sm font-semibold text-white/80 hover:text-white border border-white/15 rounded-md hover:border-white/30 transition-all duration-200">
            Masuk
          </Link>
          <Link href="/register" class="px-5 py-2 text-sm font-semibold bg-[#FF3366] text-white rounded-md hover:bg-[#e02d59] transition-all duration-200 shadow-lg shadow-[#FF3366]/20">
            Daftar Gratis
          </Link>
        </template>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
      <!-- Aurora Glows -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -left-32 w-[500px] h-[500px] bg-[#FF3366]/20 rounded-full blur-[140px] animate-pulse"></div>
        <div class="absolute bottom-1/4 -right-32 w-[500px] h-[500px] bg-[#1A56FF]/20 rounded-full blur-[140px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#6C63FF]/10 rounded-full blur-[160px]"></div>
        <div class="absolute inset-0 opacity-[0.05]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 80px 80px;"></div>
      </div>

      <div class="relative z-10 max-w-7xl mx-auto px-8 text-center">
        <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-md px-4 py-2 mb-8 text-sm text-white/60">
          <span class="w-2 h-2 bg-[#00D4AA] rounded-full animate-pulse"></span>
          Platform Freelancer #1 di Indonesia
        </div>

        <h1 class="text-6xl md:text-8xl font-black leading-none tracking-tighter mb-6 uppercase">
          <span class="block text-white">Wujudkan</span>
          <span class="block text-[#FF3366]">Kreasi Terbaik</span>
          <span class="block text-white">Bersamamu.</span>
        </h1>

        <p class="text-white/50 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
          Temukan fotografer, desainer, developer, dan kreator terbaik. Satu platform untuk semua kebutuhan kreatifmu.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link href="/register" class="group inline-flex items-center gap-3 bg-[#FF3366] text-white px-8 py-4 rounded-md font-bold text-base hover:bg-[#e02d59] transition-all duration-200 shadow-xl shadow-[#FF3366]/20">
            Mulai Sekarang
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
          </Link>
          <Link href="/layanan" class="inline-flex items-center gap-3 border border-white/15 text-white/70 px-8 py-4 rounded-md font-bold text-base hover:border-white/30 hover:text-white transition-all duration-200">
            Lihat Layanan
          </Link>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-32 relative overflow-hidden">
      <!-- Section Glows -->
      <div class="absolute top-1/2 left-0 w-96 h-96 bg-[#1A56FF]/10 rounded-full blur-[120px] pointer-events-none"></div>
      <div class="absolute top-1/2 right-0 w-96 h-96 bg-[#FF3366]/10 rounded-full blur-[120px] pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-8 relative z-10">
        <div class="flex flex-col items-center mb-20 text-center">
          <h2 class="text-[10px] font-black uppercase tracking-[0.5em] text-[#FF3366] mb-4">Pilihan Kategori</h2>
          <div class="text-5xl font-black uppercase tracking-tighter mb-4">Eksplorasi <span class="text-white/20">Keahlian.</span></div>
          <p class="text-white/40 max-w-md text-sm font-medium leading-relaxed uppercase tracking-tight">Temukan talenta yang tepat sesuai dengan kebutuhan spesifik proyek Anda.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
          <div 
            v-for="cat in categories" 
            :key="cat.name" 
            class="group relative bg-white/5 border border-white/10 p-6 rounded-md hover:bg-white/10 hover:border-[#FF3366]/50 transition-all duration-300 cursor-pointer overflow-hidden flex flex-col items-center justify-center min-h-[140px] text-center"
          >
            <!-- Hover Glow -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#FF3366]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="text-2xl mb-3 group-hover:scale-110 transition-transform duration-300">{{ cat.icon }}</div>
            <div class="text-sm font-black uppercase tracking-widest text-white group-hover:text-[#FF3366] transition-colors break-words w-full px-2">
              {{ cat.name }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="pb-20 relative z-10 border-b border-white/5">
      <div class="max-w-7xl mx-auto px-8 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div v-for="stat in stats" :key="stat.label" class="text-center group">
          <div class="text-4xl font-black text-white mb-2 tracking-tighter group-hover:text-[#FF3366] transition-colors">{{ stat.value }}</div>
          <div class="text-[10px] font-black uppercase tracking-[0.3em] text-white/30">{{ stat.label }}</div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-40 max-w-7xl mx-auto px-8 relative z-10">
      <div class="flex flex-col md:flex-row justify-between items-end mb-24 gap-8">
        <div class="max-w-2xl">
          <span class="text-[#FF3366] text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">Elite Talent Showcase</span>
          <h2 class="text-6xl font-black uppercase tracking-tighter text-white leading-none">Talenta Terbaik. <br/> <span class="text-white/20">Hasil Tanpa Kompromi.</span></h2>
        </div>
        <Link href="/layanan" class="text-white/40 hover:text-white font-black uppercase tracking-widest text-[10px] border-b border-white/10 pb-2 transition-all">Lihat Semua Katalog →</Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div v-for="elite in eliteServices" :key="elite.title" class="group relative">
          <!-- Card Glow Background -->
          <div class="absolute inset-0 bg-gradient-to-br from-[#FF3366]/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-700 rounded-[20px] blur-2xl"></div>
          
          <div class="relative bg-white/[0.03] border border-white/10 rounded-xl p-10 backdrop-blur-xl hover:border-[#FF3366]/40 transition-all duration-500 flex flex-col h-full">
            <div class="flex justify-between items-start mb-10">
              <div class="w-14 h-14 bg-white/5 rounded-lg flex items-center justify-center text-3xl group-hover:bg-[#FF3366]/10 group-hover:scale-110 transition-all duration-500">{{ elite.icon }}</div>
              <span class="bg-white/5 text-[8px] font-black uppercase tracking-widest px-3 py-1 rounded-full text-white/40 border border-white/10">{{ elite.tag }}</span>
            </div>
            
            <h3 class="text-2xl font-black text-white mb-4 uppercase tracking-tighter leading-tight group-hover:text-[#FF3366] transition-colors">{{ elite.title }}</h3>
            <p class="text-white/40 text-sm mb-10 leading-relaxed font-medium line-clamp-3 uppercase tracking-tight">{{ elite.desc }}</p>
            
            <div class="mt-auto pt-8 border-t border-white/5 flex items-center justify-between">
              <div>
                <div class="text-[8px] font-black text-white/20 uppercase tracking-widest mb-1">Starting From</div>
                <div class="text-white font-black text-2xl tracking-tighter">{{ elite.price }}</div>
              </div>
              <Link :href="elite.link" class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center hover:bg-[#FF3366] hover:border-[#FF3366] text-white transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-32 relative px-8">
      <div class="max-w-4xl mx-auto text-center bg-gradient-to-br from-white/[0.05] to-transparent border border-white/10 p-20 rounded-xl backdrop-blur-md overflow-hidden relative">
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#FF3366]/20 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-[#1A56FF]/15 rounded-full blur-[100px]"></div>
        
        <h2 class="text-5xl md:text-7xl font-black mb-8 leading-tight uppercase tracking-tighter">Mulai <br/> <span class="text-[#FF3366]">Berkolaborasi.</span></h2>
        <p class="text-white/40 text-lg mb-12 max-w-xl mx-auto font-medium leading-relaxed">Bergabunglah dengan ekosistem kreator terbesar dan mulai wujudkan proyek impianmu sekarang juga.</p>
        <Link href="/register" class="inline-flex items-center gap-3 bg-[#FF3366] text-white px-12 py-5 rounded-md font-black text-lg hover:bg-[#e02d59] transition-all duration-300 shadow-2xl shadow-[#FF3366]/30">
          DAFTAR GRATIS SEKARANG
        </Link>
      </div>
    </section>

    <!-- Footer -->
    <footer class="py-24 px-8 border-t border-white/5 relative z-10 bg-black/40">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-12 text-center md:text-left">
        <div class="text-3xl font-black tracking-tighter">
          <span class="text-white">KERJA</span><span class="text-[#FF3366]">//</span><span class="text-white">IN</span>
          <div class="text-[10px] text-white/20 mt-2 font-bold uppercase tracking-[0.4em]">Premium Marketplace</div>
        </div>
        <div class="flex gap-12">
          <a v-for="l in ['Tentang', 'Layanan', 'FAQ', 'Blog']" :key="l" href="#" class="text-white/40 hover:text-white text-[10px] font-black uppercase tracking-widest transition-colors">{{ l }}</a>
        </div>
        <div class="text-white/20 text-[10px] font-black uppercase tracking-[0.5em]">© 2026 KerjaIn Platform. All Rights Reserved.</div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth || { user: null });

const categories = [
  { name: 'Desain', icon: '🎨' },
  { name: 'Development', icon: '💻' },
  { name: 'Fotografi', icon: '📸' },
  { name: 'Video', icon: '🎥' },
  { name: 'Writing', icon: '✍️' },
  { name: 'Marketing', icon: '📈' }
]

const stats = [
    { label: 'Active Talent', value: '1,200+' },
    { label: 'Project Done', value: '8.4k+' },
    { label: 'Global Client', value: '450+' },
    { label: 'Avg. Rating', value: '4.9/5' }
]

const eliteServices = [
    { 
        title: 'Fullstack Web Architecture', 
        desc: 'Membangun sistem web skala besar dengan performa tinggi dan keamanan tingkat enterprise.', 
        icon: '⚡', 
        tag: 'Development',
        price: 'Rp 2.5jt+',
        link: '/layanan'
    },
    { 
        title: 'Cinematic Brand Identity', 
        desc: 'Produksi video iklan kelas dunia untuk meningkatkan prestise dan engagement brand Anda.', 
        icon: '🎬', 
        tag: 'Videography',
        price: 'Rp 5.0jt+',
        link: '/layanan'
    },
    { 
        title: 'Strategic Growth Marketing', 
        desc: 'Data-driven marketing untuk akselerasi bisnis Anda melalui kampanye digital yang terukur.', 
        icon: '💎', 
        tag: 'Marketing',
        price: 'Rp 1.5jt+',
        link: '/layanan'
    }
]
</script>
