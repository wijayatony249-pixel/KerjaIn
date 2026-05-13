import axios from 'axios'
window.axios = axios
window.axios.defaults.withCredentials = true
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['Accept'] = 'application/json'

import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher
// Aktifkan log Pusher di console browser untuk mempermudah debbuging jika masih gagal
window.Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || '8cb3d006062fec6ef88a',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'ap1',
    forceTLS: true
})
