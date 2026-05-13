<template>
  <div class="fixed bottom-8 right-8 z-[100] flex flex-col gap-4 pointer-events-none">
    <TransitionGroup 
      enter-active-class="transform transition duration-400 ease-out"
      enter-from-class="translate-y-8 opacity-0 scale-90"
      enter-to-class="translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-300 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-90 translate-x-8"
    >
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        :class="[
          'pointer-events-auto relative flex items-center gap-5 px-6 py-5 bg-[#0a0a0a]/90 backdrop-blur-2xl border min-w-[350px] overflow-hidden group shadow-[0_20px_50px_rgba(0,0,0,0.5)]',
          toast.type === 'message' ? 'rounded-2xl rounded-br-sm border-[#6C63FF]/30' : 
          toast.type === 'booking' ? 'rounded-xl border-[#FF3366]/40' : 
          toast.type === 'error' ? 'rounded-xl border-red-500/30' : 
          'rounded-xl border-white/10'
        ]"
      >
        <!-- Progress Bar -->
        <div 
            class="absolute bottom-0 left-0 h-1 transition-all duration-[3000ms] linear w-full origin-left"
            :class="[
                toast.type === 'message' ? 'bg-[#6C63FF]' : 
                toast.type === 'error' ? 'bg-red-500' : 
                'bg-[#FF3366]'
            ]"
            style="animation: toast-progress 3s linear forwards"
        ></div>

        <!-- Icon -->
        <div :class="[
          'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 border transition-transform group-hover:scale-110 duration-500',
          toast.type === 'message' ? 'bg-[#6C63FF]/10 text-[#6C63FF] border-[#6C63FF]/30' : 
          toast.type === 'error' ? 'bg-red-500/10 text-red-500 border-red-500/30' : 
          toast.type === 'booking' ? 'bg-[#FF3366]/10 text-[#FF3366] border-[#FF3366]/30' :
          'bg-green-500/10 text-green-500 border-green-500/30'
        ]">
          <!-- Message Icon -->
          <svg v-if="toast.type === 'message'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
          <!-- Error Icon -->
          <svg v-else-if="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <!-- Booking Icon -->
          <svg v-else-if="toast.type === 'booking'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <!-- Default Success Icon -->
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>

        <!-- Message -->
        <div class="flex-1">
          <div :class="[
            'text-[9px] font-black uppercase tracking-[0.2em] mb-1',
            toast.type === 'message' ? 'text-[#6C63FF]/70' : 
            toast.type === 'booking' ? 'text-[#FF3366]/70' : 
            toast.type === 'error' ? 'text-red-500/70' : 
            'text-green-500/70'
          ]">
            {{ 
                toast.type === 'message' ? 'New Message' : 
                toast.type === 'booking' ? 'New Order' : 
                toast.type === 'error' ? 'System Failure' : 
                'System Success' 
            }}
          </div>
          <div class="text-sm font-black text-white uppercase tracking-tighter leading-tight line-clamp-2">{{ toast.message }}</div>
        </div>

        <!-- Close Button -->
        <button @click="removeToast(toast.id)" class="text-white/20 hover:text-white transition-all p-2 hover:bg-white/10 rounded-md">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToast } from '../composables/useToast';

const { toasts, removeToast } = useToast();
</script>

<style scoped>
@keyframes toast-progress {
    from { transform: scaleX(1); }
    to { transform: scaleX(0); }
}

.linear {
    transition-timing-function: linear;
}
</style>
