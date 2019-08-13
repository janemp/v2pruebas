import 'babel-polyfill'
import Vue from 'vue'
import Vuetify from 'vuetify'
import router from '~/router/index'
import store from '~/store/index'
import VuetifyToast from 'vuetify-toast-snackbar'
import '~/plugins/index'
import es from './es.js'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import acl from '~/router/acl'
Vue.config.productionTip = false

const moment = require('moment')
require('moment/locale/es') 
Vue.use(require('vue-moment'), {
    moment
})

Vue.use(Vuetify, {
	lang: {
    locales: { es },
    current: 'es'
  },
  theme: {
    primary: "#689F38",
    secondary: "#8BC34A",
    tertiary: '#DCEDC8',
    accent: "#FF5252",
    info: "#00838f",
    error: "#e53935",
    danger: '#b71c1c',
    warning: "#f4511e",
    success: "#1b5e20",
    normal: '#F5F5F5'
  }
})
Vue.use(VuetifyToast)

export const app = new Vue({
  router,
  acl,
  store,
  render: h => h(require('$comp/App'))
}).$mount('#app')
