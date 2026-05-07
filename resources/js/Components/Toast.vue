<template>
  <Transition
    enter-active-class="transition-all duration-500 ease-out"
    enter-from-class="translate-y-4 opacity-0"
    enter-to-class="translate-y-0 opacity-100"
    leave-active-class="transition-all duration-300 ease-in"
    leave-from-class="translate-y-0 opacity-100"
    leave-to-class="translate-y-4 opacity-0"
  >
    <div
      v-if="visible"
      :class="[
        'fixed bottom-6 right-6 z-[9999] flex items-start gap-3 px-5 py-4 rounded-xl border shadow-2xl max-w-sm',
        type === 'success'
          ? 'bg-[#0d1f17] border-[#00D4AA]/30 shadow-[#00D4AA]/10'
          : 'bg-[#1f0d12] border-[#FF3366]/30 shadow-[#FF3366]/10'
      ]"
    >
      <!-- Icon -->
      <div :class="['w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5', type === 'success' ? 'bg-[#00D4AA]/15' : 'bg-[#FF3366]/15']">
        <svg v-if="type === 'success'" class="w-4 h-4 text-[#00D4AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <svg v-else class="w-4 h-4 text-[#FF3366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </div>

      <!-- Content -->
      <div class="flex-1 min-w-0">
        <p class="text-white text-sm font-semibold leading-snug">{{ message }}</p>
      </div>

      <!-- Close button -->
      <button @click="visible = false" class="text-white/30 hover:text-white/60 transition-colors shrink-0 mt-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <!-- Progress bar -->
      <div
        :class="['absolute bottom-0 left-0 h-0.5 rounded-b-xl transition-all', type === 'success' ? 'bg-[#00D4AA]' : 'bg-[#FF3366]']"
        :style="{ width: progress + '%' }"
      ></div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
  message: String,
  type: {
    type: String,
    default: 'success',
  },
  duration: {
    type: Number,
    default: 4000,
  },
});

const visible = ref(false);
const progress = ref(100);
let timer = null;
let progressTimer = null;

const startTimer = () => {
  if (!props.message) return;
  visible.value = true;
  progress.value = 100;

  const startTime = Date.now();
  progressTimer = setInterval(() => {
    const elapsed = Date.now() - startTime;
    progress.value = Math.max(0, 100 - (elapsed / props.duration) * 100);
  }, 50);

  timer = setTimeout(() => {
    visible.value = false;
    clearInterval(progressTimer);
  }, props.duration);
};

onMounted(() => {
  if (props.message) startTimer();
});

watch(() => props.message, (val) => {
  if (val) {
    clearTimeout(timer);
    clearInterval(progressTimer);
    startTimer();
  }
});
</script>
