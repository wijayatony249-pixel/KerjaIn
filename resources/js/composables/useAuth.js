import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

export function useAuth() {
  const page = usePage()
  const user = computed(() => page.props.auth?.user)
  const isFreelancer = computed(() => user.value?.role === 'freelancer')
  const isClient = computed(() => user.value?.role === 'client')
  
  return { user, isFreelancer, isClient }
}
