<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/90 backdrop-blur-xl">
      <div class="bg-[#0f0f0f] border border-white/10 p-12 rounded-xl w-full max-w-md shadow-2xl relative overflow-hidden text-center">
        <!-- Background Glow -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#FF3366]/5 rounded-full blur-[100px] pointer-events-none"></div>
        
        <button @click="$emit('close')" class="absolute top-6 right-6 text-white/20 hover:text-white transition-all p-2 hover:bg-white/5 rounded-md z-30">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Icon -->
        <div class="w-20 h-20 bg-[#FF3366]/10 rounded-xl flex items-center justify-center mx-auto mb-8 border border-[#FF3366]/20">
          <svg class="w-10 h-10 text-[#FF3366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
        </div>

        <h2 class="text-3xl font-black uppercase tracking-tighter text-white mb-4">{{ title }}</h2>
        <p class="text-white/40 text-sm font-medium leading-relaxed uppercase tracking-wide mb-10">{{ message }}</p>

        <div class="flex gap-4">
          <button 
            @click="$emit('close')"
            class="flex-1 bg-white/5 text-white font-black py-4 rounded-md hover:bg-white/10 transition-all uppercase tracking-widest text-[10px] border border-white/10"
          >
            Batal
          </button>
          <button 
            @click="$emit('confirm')"
            class="flex-1 bg-[#FF3366] text-white font-black py-4 rounded-md hover:bg-[#e02d59] transition-all uppercase tracking-widest text-[10px] shadow-lg shadow-[#FF3366]/20"
          >
            Konfirmasi
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  show: Boolean,
  title: {
    type: String,
    default: 'Konfirmasi'
  },
  message: {
    type: String,
    default: 'Apakah Anda yakin ingin melakukan aksi ini?'
  }
})

defineEmits(['confirm', 'close'])
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(20px);
}
</style>
