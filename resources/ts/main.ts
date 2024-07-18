import 'jsvectormap/dist/jsvectormap.min.css'
import 'flatpickr/dist/flatpickr.min.css'
import { createPinia } from "pinia"
import { createApp } from 'vue'
import VueApexCharts from 'vue3-apexcharts'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import floatingVue from "floating-vue";
import App from './App.vue'
import router from './router'
import form from "@/forms/initForms"

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
app.use(form.plugin,form.config)
app.component('FontAwesomeIcon',FontAwesomeIcon)
app.mount('#app')
