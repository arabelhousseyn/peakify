

require('./bootstrap');

window.Vue = require('vue').default;

import VueToast from 'vue-toast-notification'

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


import vuetify from './plugins/vuetify'

import router from './router/index'
import store from './store/index'

window.Vue.use(VueToast)

import 'vue-toast-notification/dist/theme-sugar.css'


const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store
});
