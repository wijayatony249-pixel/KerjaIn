import { ref, computed } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const user = ref(null);
const token = ref(localStorage.getItem('kerjain_token'));

export function useAuth() {
    const isAuthenticated = computed(() => !!token.value);

    const axiosAuth = () => {
        return axios.create({
            baseURL: '/api',
            headers: {
                Authorization: `Bearer ${token.value}`,
                Accept: 'application/json',
            }
        });
    };

    const login = async (credentials) => {
        try {
            console.log('Attempting login for:', credentials.email);
            const response = await axios.post('/api/login', credentials);
            token.value = response.data.token;
            localStorage.setItem('kerjain_token', token.value);
            user.value = response.data.user;
            console.log('Login success!');
            return { success: true };
        } catch (error) {
            console.error('Login error:', error.response?.data);
            return {
                success: false,
                errors: error.response?.data?.errors || { message: error.response?.data?.message || 'Login failed' }
            };
        }
    };

    const register = async (userData) => {
        try {
            await axios.post('/api/register', userData);
            return { success: true };
        } catch (error) {
            return {
                success: false,
                errors: error.response?.data?.errors || { message: 'Registration failed' }
            };
        }
    };

    const logout = () => {
        localStorage.removeItem('kerjain_token');
        token.value = null;
        user.value = null;
        window.location.href = '/login';
    };

    const fetchUser = async () => {
        if (!token.value) return;
        try {
            const response = await axiosAuth().get('/me');
            user.value = response.data;
        } catch (error) {
            logout();
        }
    };

    return {
        user,
        token,
        isAuthenticated,
        axiosAuth,
        login,
        register,
        logout,
        fetchUser
    };
}
