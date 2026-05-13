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
            v-for="item in navItems"
            :key="item.label"
            :href="item.href"
            :class="[
              'flex items-center gap-4 px-4 py-3 rounded-md text-xs font-bold uppercase tracking-widest transition-all duration-200 group',
              isActive(item.href) ? 'bg-[#FF3366] text-white shadow-lg shadow-[#FF3366]/20' : 'text-white/40 hover:text-white hover:bg-white/5'
            ]"
          >
            <component :is="item.icon" class="w-5 h-5 transition-transform group-hover:scale-110" />
            {{ item.label }}
          </Link>
        </nav>
      </div>

      <div class="mt-auto p-8 border-t border-white/5">
        <div class="flex items-center gap-3 mb-6 p-2 rounded-md bg-white/5 border border-white/10">
          <div class="w-10 h-10 rounded-md bg-gradient-to-br from-[#FF3366] to-[#6C63FF] flex items-center justify-center font-black text-sm overflow-hidden border border-white/10">
            <img v-if="user?.avatar_url" :src="user.avatar_url" class="w-full h-full object-cover" />
            <span v-else>{{ user?.name?.[0] || '?' }}</span>
          </div>
          <div class="overflow-hidden text-ellipsis">
            <div class="text-xs font-black truncate text-white">{{ user?.name }}</div>
            <div class="text-[10px] text-white/40 font-bold uppercase tracking-tighter">{{ user?.role }}</div>
          </div>
        </div>
        
        <Link
          href="/logout"
          method="post"
          as="button"
          class="w-full flex items-center gap-3 px-4 py-3 rounded-md text-[10px] font-black uppercase tracking-widest text-[#FF3366] hover:bg-[#FF3366]/10 transition-all duration-200"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Keluar
        </Link>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8 relative min-h-screen overflow-hidden">
      <!-- Background Aurora Glows -->
      <div class="fixed top-0 left-64 w-full h-full pointer-events-none z-0 overflow-hidden bg-[#0a0a0a]">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.03] mix-blend-overlay"></div>
        <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] bg-[#FF3366]/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute top-[10%] -right-[20%] w-[60%] h-[60%] bg-[#6C63FF]/15 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[0%] left-[10%] w-[80%] h-[50%] bg-[#FF3366]/10 rounded-full blur-[150px]"></div>
      </div>
      
      <header class="flex items-center justify-between mb-12 relative z-10">
        <h1 class="text-4xl font-black uppercase tracking-tighter text-white">{{ title }}</h1>
        
        <div class="flex items-center gap-6">
          <div class="flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2 rounded-md">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-white/60">Sistem Online</span>
          </div>
        </div>
      </header>

      <div class="relative z-10">
        <slot />
      </div>
    </main>

    <!-- Global Toast List -->
    <ToastList />
  </div>
</template>

<script setup>
import { ref, computed, watch, markRaw } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { useAuth } from '../composables/useAuth'
import { useToast } from '../composables/useToast'
import ToastList from '../Components/ToastList.vue'

import IconSquares from '../Icons/IconSquares.vue'
import IconBriefcase from '../Icons/IconBriefcase.vue'
import IconCalendar from '../Icons/IconCalendar.vue'
import IconPhoto from '../Icons/IconPhoto.vue'
import IconUser from '../Icons/IconUser.vue'

const props = defineProps({
  title: String
})

const { user, isFreelancer } = useAuth()
const { addToast } = useToast()
const page = usePage()

const navItems = computed(() => {
  const items = [
    { label: 'Dashboard', href: '/dashboard', icon: markRaw(IconSquares) },
    { label: 'Layanan', href: '/layanan', icon: markRaw(IconBriefcase) },
    { label: 'Pesanan', href: '/booking', icon: markRaw(IconCalendar) },
    { label: 'Profil', href: '/profil', icon: markRaw(IconUser) },
  ]

  if (isFreelancer.value) {
    items.splice(2, 0, 
      { label: 'Layanan Saya', href: '/layanan/kelola', icon: markRaw(IconBriefcase) },
      { label: 'Portfolio', href: '/portfolio', icon: markRaw(IconPhoto) }
    )
  }

  return items
})

const isActive = (href) => {
  // Exact match or match with query parameters
  if (page.url === href || page.url.startsWith(href + '?')) return true
  
  // Specific case for /layanan vs /layanan/kelola
  if (href === '/layanan' && page.url.startsWith('/layanan/kelola')) return false
  
  // Sub-route match (e.g. /layanan/123 matches /layanan)
  return page.url.startsWith(href + '/')
}

const flashSuccess = computed(() => page.props.flash?.success)
const flashError = computed(() => page.props.flash?.error)

watch(flashSuccess, (msg) => {
    if (msg) addToast(msg, 'success')
}, { immediate: true })

watch(flashError, (msg) => {
    if (msg) addToast(msg, 'error')
}, { immediate: true })

import { onMounted } from 'vue'

onMounted(() => {
  if (user.value?.id) {
    window.Echo.private(`App.Models.User.${user.value.id}`)
      .listen('.message.sent', (e) => {
        // Jangan munculkan toast jika sedang membuka halaman chat booking tersebut
        const isCurrentBooking = window.location.pathname.includes(`/booking/${e.message.booking_id}`)
        if (!isCurrentBooking && e.message.sender_id !== user.value.id) {
            addToast(`Pesan baru dari ${e.message.sender.name}`, 'success')
        }
      })
      .listen('.booking.created', (e) => {
          addToast(`Pesanan baru! ${e.booking.client.name} memesan ${e.booking.service.title}`, 'success')
      })
  }
})
</script>

<style>
.toast-enter-active, .toast-leave-active {
  transition: all 0.5s ease;
}
.toast-enter-from, .toast-leave-to {
  transform: translateY(30px);
  opacity: 0;
}
</style>
