import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
Vue.use(Vuetify)

export default new Vuetify({
    theme: { dark:  false,
        themes: {
            light: {
                primary: '#1976d3',
                secondary: '#1564c0',
                white : '#FFF',
                black : '#000',
                background : '#d2d2d2'
            },
        },
    },
})
