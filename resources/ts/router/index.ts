import { createRouter, createWebHistory } from 'vue-router'

import SigninView from '@/views/Authentication/SigninView.vue'
// import SignupView from '@/views/Authentication/SignupView.vue'
// import CalendarView from '@/views/CalendarView.vue'
// import BasicChartView from '@/views/Charts/BasicChartView.vue'
import ECommerceView from '@/views/Dashboard/ECommerceView.vue'
// import FormElementsView from '@/views/Forms/FormElementsView.vue'
// import FormLayoutView from '@/views/Forms/FormLayoutView.vue'
// import SettingsView from '@/views/Pages/SettingsView.vue'
// import ProfileView from '@/views/ProfileView.vue'
// import TablesView from '@/views/TablesView.vue'
// import AlertsView from '@/views/UiElements/AlertsView.vue'
// import ButtonsView from '@/views/UiElements/ButtonsView.vue'

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: ECommerceView,
        meta: {
            layout: 'DashboardLayout',
            title: 'eCommerce Dashboard',
        }
    },
    //   {
    //     path: '/calendar',
    //     name: 'calendar',
    //     component: CalendarView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Calendar'
    //     }
    //   },
    //   {
    //     path: '/profile',
    //     name: 'profile',
    //     component: ProfileView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Profile'
    //     }
    //   },
    //   {
    //     path: '/forms/form-elements',
    //     name: 'formElements',
    //     component: FormElementsView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Form Elements'
    //     }
    //   },
    //   {
    //     path: '/forms/form-layout',
    //     name: 'formLayout',
    //     component: FormLayoutView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Form Layout'
    //     }
    //   },
    //   {
    //     path: '/tables',
    //     name: 'tables',
    //     component: TablesView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Tables'
    //     }
    //   },
    //   {
    //     path: '/pages/settings',
    //     name: 'settings',
    //     component: SettingsView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Settings'
    //     }
    //   },
    //   {
    //     path: '/charts/basic-chart',
    //     name: 'basicChart',
    //     component: BasicChartView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Basic Chart'
    //     }
    //   },
    //   {
    //     path: '/ui-elements/alerts',
    //     name: 'alerts',
    //     component: AlertsView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Alerts'
    //     }
    //   },
    //   {
    //     path: '/ui-elements/buttons',
    //     name: 'buttons',
    //     component: ButtonsView,
    //     meta: {
    //       layout: 'DashboardLayout',
    //       title: 'Buttons'
    //     }
    //   },
    {
        path: '/auth/signin',
        name: 'signin',
        component: SigninView,
        meta: {
            layout: 'DefaultLayout',
            title: 'Signin'
        }
    },
    //   {
    //     path: '/auth/signup',
    //     name: 'signup',
    //     component: SignupView,
    //     meta: {
    //       layout: 'DefaultLayout',
    //       title: 'Signup'
    //     }
    //   }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { left: 0, top: 0 }
    }
})

router.beforeEach((to, from, next) => {
    document.title = `Vue.js ${to.meta.title} | TailAdmin - Vue.js Tailwind CSS Dashboard Template`
    next()
})

export default router