import 'jsvectormap/dist/jsvectormap.min.css'
import 'flatpickr/dist/flatpickr.min.css'
import { createPinia } from "pinia"
import { createApp } from 'vue'
import VueApexCharts from 'vue3-apexcharts'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import floatingVue from "floating-vue";
import Vue3Toastify from 'vue3-toastify';
import App from './App.vue'
import router from './router'
import form from "@/forms/initForms"
import { createVfm } from 'vue-final-modal'


const app = createApp(App);

app.use(createPinia())
app.use(router)
app.use(VueApexCharts)
app.use(floatingVue, {
    themes: {
        sidebar: {
          '$extend': 'menu',
        },
      },
})
app.use(Vue3Toastify, {theme: "colored", position:"bottom-right", clearOnUrlChange:false})
app.use(createVfm())
app.use(form.plugin,form.config)
app.component('FontAwesomeIcon',FontAwesomeIcon)
app.mount('#app')
