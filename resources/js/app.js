

require('./bootstrap');

window.Vue = require('vue').default;

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


import vuetify from './plugins/vuetify'

import router from './router/index'
import store from './store/index'


const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store
});
