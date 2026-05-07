<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white overflow-x-hidden" style="font-family: 'Inter', sans-serif;">

    <!-- Toast Notification -->
    <Toast
      v-if="flash.success"
      :message="flash.success"
      type="success"
    />
    <Toast
      v-if="flash.error"
      :message="flash.error"
      type="error"
    />

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 px-8 py-5 flex items-center justify-between backdrop-blur-md bg-[#0a0a0a]/80 border-b border-white/5">
      <div class="text-2xl font-black tracking-tighter">
        <span class="text-white">KERJA</span><span class="text-[#FF3366]">//</span><span class="text-white">IN</span>
      </div>
      <div class="hidden md:flex gap-8 items-center">
        <a href="#services" class="text-white/60 hover:text-white text-sm font-medium transition-colors duration-200">Jelajahi</a>
        <a href="#how" class="text-white/60 hover:text-white text-sm font-medium transition-colors duration-200">Cara Kerja</a>
        <a href="#about" class="text-white/60 hover:text-white text-sm font-medium transition-colors duration-200">Tentang</a>
      </div>
      <div class="flex gap-3">
        <!-- If logged in -->
        <template v-if="auth.user">
          <span class="px-4 py-2 text-sm text-white/50">
            Halo, <span class="text-white font-semibold">{{ auth.user.name }}</span>
          </span>
          <Link href="/dashboard" class="px-5 py-2 text-sm font-semibold bg-[#FF3366] text-white rounded-md hover:bg-[#e02d59] transition-all duration-200">
            Dashboard
          </Link>
        </template>
        <!-- If guest -->
        <template v-else>
          <Link href="/login" class="px-5 py-2 text-sm font-semibold text-white/80 hover:text-white border border-white/15 rounded-md hover:border-white/30 transition-all duration-200">
            Masuk
          </Link>
          <Link href="/register" class="px-5 py-2 text-sm font-semibold bg-[#FF3366] text-white rounded-md hover:bg-[#e02d59] transition-all duration-200">
            Daftar Gratis
          </Link>
        </template>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -left-32 w-96 h-96 bg-[#FF3366]/15 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-[#6C63FF]/15 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 60px 60px;"></div>
      </div>

      <div class="relative z-10 max-w-7xl mx-auto px-8 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-md px-4 py-2 mb-8 text-sm text-white/60">
          <span class="w-2 h-2 bg-[#00D4AA] rounded-full animate-pulse"></span>
          Platform Freelancer #1 di Indonesia
        </div>

        <h1 class="text-6xl md:text-8xl font-black leading-none tracking-tighter mb-6">
          <span class="block text-white">Wujudkan</span>
          <span class="block text-[#FF3366]">Kreasi Terbaik</span>
          <span class="block text-white">Bersamamu.</span>
        </h1>

        <p class="text-white/50 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
          Temukan fotografer, desainer, developer, dan kreator terbaik. Satu platform untuk semua kebutuhan kreatifmu.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link href="/register" class="group inline-flex items-center gap-3 bg-[#FF3366] text-white px-8 py-4 rounded-md font-bold text-base hover:bg-[#e02d59] transition-all duration-200">
            Mulai Sekarang
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
          </Link>
          <Link href="/layanan" class="inline-flex items-center gap-3 border border-white/15 text-white/70 px-8 py-4 rounded-md font-bold text-base hover:border-white/30 hover:text-white transition-all duration-200">
            Lihat Layanan
          </Link>
        </div>

        <!-- Floating category cards -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
          <Link href="/layanan?search=Desain" class="bg-white/5 border border-white/10 rounded-xl p-5 text-left hover:bg-white/8 hover:border-white/20 transition-all duration-300 cursor-pointer">
            <div class="text-3xl mb-3">🎨</div>
            <div class="text-white font-semibold text-sm">Desain & Branding</div>
            <div class="text-white/40 text-xs mt-1">Jelajahi talent terbaik</div>
          </Link>
          <Link href="/layanan?search=Fotografi" class="bg-white/5 border border-white/10 rounded-xl p-5 text-left hover:bg-white/8 hover:border-white/20 transition-all duration-300 cursor-pointer -translate-y-0 md:-translate-y-2">
            <div class="text-3xl mb-3">📸</div>
            <div class="text-white font-semibold text-sm">Fotografi & Video</div>
            <div class="text-white/40 text-xs mt-1">Abadikan momenmu</div>
          </Link>
          <Link href="/layanan?search=Development" class="bg-white/5 border border-white/10 rounded-xl p-5 text-left hover:bg-white/8 hover:border-white/20 transition-all duration-300 cursor-pointer">
            <div class="text-3xl mb-3">💻</div>
            <div class="text-white font-semibold text-sm">Web & Development</div>
            <div class="text-white/40 text-xs mt-1">Bangun platform digital</div>
          </Link>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 border-y border-white/5 bg-white/[0.02]">
      <div class="max-w-7xl mx-auto px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div>
            <div class="text-4xl font-black text-[#FF3366] mb-1">2,400<span class="text-2xl">+</span></div>
            <div class="text-white/40 text-sm">Freelancer Aktif</div>
          </div>
          <div>
            <div class="text-4xl font-black text-[#6C63FF] mb-1">8,900<span class="text-2xl">+</span></div>
            <div class="text-white/40 text-sm">Project Selesai</div>
          </div>
          <div>
            <div class="text-4xl font-black text-[#00D4AA] mb-1">4.9<span class="text-2xl">★</span></div>
            <div class="text-white/40 text-sm">Rating Rata-rata</div>
          </div>
          <div>
            <div class="text-4xl font-black text-white mb-1">120<span class="text-2xl">+</span></div>
            <div class="text-white/40 text-sm">Kategori Layanan</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 max-w-7xl mx-auto px-8">
      <div class="mb-3">
        <span class="text-[#FF3366] text-sm font-bold uppercase tracking-widest">Layanan Populer</span>
      </div>
      <div class="flex items-end justify-between mb-14">
        <h2 class="text-5xl font-black leading-tight">Temukan Jasa<br/><span class="text-white/30">yang Kamu Butuhkan</span></h2>
        <Link href="/layanan" class="text-white/40 hover:text-white text-sm font-medium transition-colors hidden md:block">Lihat semua →</Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="group border border-white/10 rounded-xl p-6 hover:border-[#FF3366]/40 transition-all duration-300 cursor-pointer bg-white/[0.03]">
          <span class="inline-block bg-[#FF3366]/10 text-[#FF3366] text-xs font-bold px-3 py-1 rounded mb-4">DESAIN</span>
          <h3 class="text-xl font-bold text-white mb-1">Desain Logo & Brand</h3>
          <p class="text-white/40 text-sm mb-5">oleh Creative Studio</p>
          <div class="flex items-center justify-between">
            <span class="text-white font-black text-xl">Mulai Rp 500rb</span>
            <Link href="/layanan" class="bg-[#FF3366] text-white text-sm font-bold px-4 py-2 rounded-md hover:bg-[#e02d59] transition-colors">Pesan</Link>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="group border border-white/10 rounded-xl p-6 hover:border-[#6C63FF]/40 transition-all duration-300 cursor-pointer bg-white/[0.03]">
          <span class="inline-block bg-[#6C63FF]/10 text-[#6C63FF] text-xs font-bold px-3 py-1 rounded mb-4">FOTOGRAFI</span>
          <h3 class="text-xl font-bold text-white mb-1">Fotografi Produk</h3>
          <p class="text-white/40 text-sm mb-5">oleh Photo Pro</p>
          <div class="flex items-center justify-between">
            <span class="text-white font-black text-xl">Mulai Rp 1.5jt</span>
            <Link href="/layanan" class="bg-[#6C63FF] text-white text-sm font-bold px-4 py-2 rounded-md hover:bg-[#5a52e0] transition-colors">Pesan</Link>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="group border border-white/10 rounded-xl p-6 hover:border-[#00D4AA]/40 transition-all duration-300 cursor-pointer bg-white/[0.03]">
          <span class="inline-block bg-[#00D4AA]/10 text-[#00D4AA] text-xs font-bold px-3 py-1 rounded mb-4">DEVELOPMENT</span>
          <h3 class="text-xl font-bold text-white mb-1">Pengembangan Web</h3>
          <p class="text-white/40 text-sm mb-5">oleh Dev Team</p>
          <div class="flex items-center justify-between">
            <span class="text-white font-black text-xl">Mulai Rp 5jt</span>
            <Link href="/layanan" class="bg-[#00D4AA] text-[#0a0a0a] text-sm font-bold px-4 py-2 rounded-md hover:bg-[#00b894] transition-colors">Pesan</Link>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section id="how" class="py-24 bg-white/[0.02] border-y border-white/5">
      <div class="max-w-7xl mx-auto px-8">
        <div class="mb-3">
          <span class="text-[#6C63FF] text-sm font-bold uppercase tracking-widest">Cara Kerja</span>
        </div>
        <h2 class="text-5xl font-black mb-16">Mudah &amp; Cepat<br/><span class="text-white/30">3 Langkah Saja</span></h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Step 1 -->
          <div class="border border-white/10 rounded-xl p-8 bg-white/[0.02] flex flex-col">
            <div class="flex items-center gap-4 mb-6">
              <div class="w-12 h-12 bg-[#FF3366]/10 border border-[#FF3366]/20 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#FF3366]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </div>
              <span class="text-5xl font-black text-white/8 leading-none select-none">01</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Cari Layanan</h3>
            <p class="text-white/40 leading-relaxed text-sm">Temukan freelancer atau layanan yang sesuai dengan kebutuhan proyek kamu dari 120+ kategori.</p>
          </div>

          <!-- Step 2 -->
          <div class="border border-white/10 rounded-xl p-8 bg-white/[0.02] flex flex-col">
            <div class="flex items-center gap-4 mb-6">
              <div class="w-12 h-12 bg-[#6C63FF]/10 border border-[#6C63FF]/20 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#6C63FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <span class="text-5xl font-black text-white/8 leading-none select-none">02</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Booking Freelancer</h3>
            <p class="text-white/40 leading-relaxed text-sm">Lakukan booking dan sepakati detail project langsung dengan freelancer pilihanmu.</p>
          </div>

          <!-- Step 3 -->
          <div class="border border-white/10 rounded-xl p-8 bg-white/[0.02] flex flex-col">
            <div class="flex items-center gap-4 mb-6">
              <div class="w-12 h-12 bg-[#00D4AA]/10 border border-[#00D4AA]/20 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#00D4AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <span class="text-5xl font-black text-white/8 leading-none select-none">03</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Terima Hasil Kerja</h3>
            <p class="text-white/40 leading-relaxed text-sm">Terima hasil kerja berkualitas tinggi dari freelancer berpengalaman tepat waktu.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-[#FF3366]/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-[#6C63FF]/10 rounded-full blur-[100px]"></div>
      </div>
      <div class="relative max-w-4xl mx-auto px-8 text-center">
        <h2 class="text-5xl md:text-6xl font-black mb-6">
          Siap Wujudkan<br/>
          <span class="text-[#FF3366]">Proyek Impianmu?</span>
        </h2>
        <p class="text-white/40 text-lg mb-10">Bergabung dengan ribuan kreator dan client yang sudah mempercayakan proyek mereka di KerjaIn.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link href="/register" class="inline-flex items-center gap-3 bg-[#FF3366] text-white px-8 py-4 rounded-md font-bold text-base hover:bg-[#e02d59] transition-colors">
            Daftar Sekarang — Gratis
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
          </Link>
          <a href="#" class="inline-flex items-center gap-3 border border-white/15 text-white/60 px-8 py-4 rounded-md font-bold text-base hover:border-white/30 hover:text-white transition-all">
            Pelajari Lebih Lanjut
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/5 py-12">
      <div class="max-w-7xl mx-auto px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-8">
          <div class="text-2xl font-black tracking-tighter">
            <span class="text-white">KERJA</span><span class="text-[#FF3366]">//</span><span class="text-white">IN</span>
          </div>
          <div class="flex gap-8">
            <a href="#" class="text-white/40 hover:text-white text-sm transition-colors">Tentang</a>
            <a href="#" class="text-white/40 hover:text-white text-sm transition-colors">Layanan</a>
            <a href="#" class="text-white/40 hover:text-white text-sm transition-colors">Kontak</a>
            <a href="#" class="text-white/40 hover:text-white text-sm transition-colors">Blog</a>
          </div>
        </div>
        <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
          <p class="text-white/20 text-sm">© 2026 KerjaIn. All rights reserved.</p>
          <div class="flex gap-3">
            <a href="#" class="w-8 h-8 bg-white/5 border border-white/10 rounded flex items-center justify-center text-white/40 hover:text-white hover:border-white/30 transition-all text-xs font-bold">F</a>
            <a href="#" class="w-8 h-8 bg-white/5 border border-white/10 rounded flex items-center justify-center text-white/40 hover:text-white hover:border-white/30 transition-all text-xs font-bold">T</a>
            <a href="#" class="w-8 h-8 bg-white/5 border border-white/10 rounded flex items-center justify-center text-white/40 hover:text-white hover:border-white/30 transition-all text-xs font-bold">IG</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Toast from '@/Components/Toast.vue';

const page = usePage();
const flash = computed(() => page.props.flash ?? {});
const auth  = computed(() => page.props.auth ?? {});
</script>
