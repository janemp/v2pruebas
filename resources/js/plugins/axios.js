import axios from 'axios'
import store from '~/store/index'
import router from '~/router/index'
import { api } from '~/config'
import { app } from '~/app'

// axios.defaults.headers.common['Accept'] = 'application/json'
// axios.defaults.headers.common['Content-Type'] = 'application/json'

// axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

axios.interceptors.request.use(config => {
  config.headers['X-Requested-With'] = 'XMLHttpRequest'
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  const token = store.getters['auth/token']
  if (token) { 
    config.headers['Authorization'] = 'Bearer ' + token
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
  }

  return config
}, error => {
  return Promise.reject(error)
})

axios.interceptors.response.use(response => {
  return response
}, async error => {
  if (store.getters['auth/token']) {
    // TODO: Find more reliable way to determine when Token state
    if (error.response.status === 401 && error.response.data.message === 'Token expirado') {
      const { data } = await axios.post(api.path('login.refresh'))
      store.dispatch('auth/saveToken', data)
      return axios.request(error.config)
    }

    if (error.response.status === 401 ||
      (error.response.status === 500 && (
        error.response.data.message === 'Token expirado y no puede actualizar' ||
        error.response.data.message === 'Token en lista negra'
      ))
    ) {
      store.dispatch('auth/destroy')
      router.push({ name: 'login' })
    }

    if (error.response.status === 403) {
      error.response.data.message = 'No tiene autorización para este recurso'
    }
  }

  error.response.data.message !== undefined && app.$toast.error(error.response.data.message || 'Ocurrió un error.')
  error.response.data.message !== undefined && console.log(error.response.data.message)
  error.response.data.error !== undefined && app.$toast.error(error.response.data.error || 'Ocurrió un error.')
  return Promise.reject(error)
})
