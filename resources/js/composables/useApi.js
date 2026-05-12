import axios from 'axios'

export function useApi() {
  const api = axios.create({ 
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
  })
  
  const ensureCsrf = async () => {
    await api.get('/sanctum/csrf-cookie')
  }
  
  const get = (url, params) => api.get('/api' + url, { params })
  
  const post = async (url, data, config) => {
    await ensureCsrf()
    return api.post('/api' + url, data, config)
  }
  
  const put = async (url, data) => {
    await ensureCsrf()
    return api.put('/api' + url, data)
  }
  
  const del = async (url) => {
    await ensureCsrf()
    return api.delete('/api' + url)
  }
  
  return { get, post, put, del }
}
