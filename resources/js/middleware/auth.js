import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

export function useRequireAuth() {
    onMounted(() => {
        const token = localStorage.getItem('kerjain_token');
        if (!token) {
            router.visit('/login');
        }
    });
}
