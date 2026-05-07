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
        class="pointer-events-auto flex items-center gap-4 px-6 py-4 bg-[#0a0a0a]/80 backdrop-blur-xl border border-white/10 rounded-lg shadow-2xl min-w-[300px]"
      >
        <!-- Icon -->
        <div :class="[
          'w-10 h-10 rounded-md flex items-center justify-center flex-shrink-0',
          toast.type === 'error' ? 'bg-red-500/20 text-red-500' : 'bg-[#FF3366]/20 text-[#FF3366]'
        ]">
          <svg v-if="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>

        <!-- Message -->
        <div class="flex-1">
          <div class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-0.5">
            {{ toast.type === 'error' ? 'Gagal' : 'Berhasil' }}
          </div>
          <div class="text-sm font-black text-white uppercase tracking-tighter">{{ toast.message }}</div>
        </div>

        <!-- Close Button -->
        <button @click="removeToast(toast.id)" class="text-white/20 hover:text-white transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useToast } from '../composables/useToast';

const { toasts, removeToast } = useToast();
</script>
