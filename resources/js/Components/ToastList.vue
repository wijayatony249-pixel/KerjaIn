<template>
  <div class="fixed bottom-8 right-8 z-[100] flex flex-col gap-3 pointer-events-none">
    <TransitionGroup 
      enter-active-class="transform transition duration-300 ease-out"
      enter-from-class="translate-y-4 opacity-0 scale-95"
      enter-to-class="translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        class="pointer-events-auto relative flex items-center gap-4 px-6 py-5 bg-[#0a0a0a]/90 backdrop-blur-2xl border border-white/10 rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] min-w-[350px] overflow-hidden group"
      >
        <!-- Progress Bar -->
        <div 
            class="absolute bottom-0 left-0 h-1 bg-[#FF3366] transition-all duration-[3000ms] linear w-full origin-left"
            :class="{'bg-red-500': toast.type === 'error'}"
            style="animation: toast-progress 3s linear forwards"
        ></div>

        <!-- Icon -->
        <div :class="[
          'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 border transition-transform group-hover:scale-110 duration-500',
          toast.type === 'error' ? 'bg-red-500/10 text-red-500 border-red-500/20' : 'bg-[#FF3366]/10 text-[#FF3366] border-[#FF3366]/20'
        ]">
          <svg v-if="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>

        <!-- Message -->
        <div class="flex-1">
          <div class="text-[9px] font-black uppercase tracking-[0.2em] text-white/30 mb-1">
            {{ toast.type === 'error' ? 'System Failure' : 'System Success' }}
          </div>
          <div class="text-sm font-black text-white uppercase tracking-tighter leading-tight">{{ toast.message }}</div>
        </div>

        <!-- Close Button -->
        <button @click="removeToast(toast.id)" class="text-white/10 hover:text-white transition-all p-2 hover:bg-white/5 rounded-md">
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
