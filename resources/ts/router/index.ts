import { createRouter, createWebHistory, RouterView } from 'vue-router'
import { useAuthStore } from "@/stores/authStore"


import SigninView from '@/views/Authentication/SigninView.vue'
// import SignupView from '@/views/Authentication/SignupView.vue'
// import CalendarView from '@/views/CalendarView.vue'
// import BasicChartView from '@/views/Charts/BasicChartView.vue'
// import FormElementsView from '@/views/Forms/FormElementsView.vue'
// import FormLayoutView from '@/views/Forms/FormLayoutView.vue'
// import SettingsView from '@/views/Pages/SettingsView.vue'
// import ProfileView from '@/views/ProfileView.vue'
import DataTable from '@/views/DataTable/TablesView.vue'
import FormShowcase from '@/views/Forms/FormShowcase.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
// import AlertsView from '@/views/UiElements/AlertsView.vue'
// import ButtonsView from '@/views/UiElements/ButtonsView.vue'

const routes = [
    {
        path: '/auth/signin',
        name: 'SignIn',
        component: SigninView,
        meta: {
            title: 'Sign In'
        }
    },
    {
        path: '/',
        name: 'Root',
        component: DashboardLayout,
        redirect: "/dashboard",
        meta: {
            title: 'Root',
        },
        children: [
            {
                path:"dashboard",
                component: () => import("@/views/Dashboard/ECommerceView.vue"),
                name:"Dashboard",
                meta: {
                    title: 'Dashboard'
                }
            },
            {
                path: 'data-table',
                name: 'DataTable',
                component: RouterView,
                redirect:"/data-table/index",
                meta: {
                    title: 'Tables'
                },
                children:[
                    {
                        path: 'index',
                        name: 'UserIndex',
                        component: () => import("@/views/DataTable/TablesView.vue"),
                        meta: {
                            layout: 'DashboardLayout',
                            title: 'Users Index / CRUD Demo'
                        }
                    },
                    {
                        path: 'create',
                        name: 'CreateUser',
                        component: () => import('@/views/User/CreateUser.vue'),
                        meta: {
                            layout: 'DashboardLayout',
                            title: 'Create User'
                        }
                    },
                    {
                        path: 'edit/:id',
                        name: 'EditUser',
                        component: () => import('@/views/User/EditUser.vue'),
                        meta: {
                            layout: 'DashboardLayout',
                            title: 'Edit User'
                        }
                    },
                ]
            },
            {
                path: '/forms',
                name: 'FormShowcase',
                component: FormShowcase,
                meta: {
                    title: 'Form Showcase'
                }
            },
        ]
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

router.beforeEach(async (to, from, next) => {
    document.title = `${to.meta.title} | Bellza Admin`
    const authStore = useAuthStore()
    if (authStore.attemptCount === 0) {
        await authStore.attempt()
    }
    if(authStore.isAuthenticated){
        if (to.name !== "SignIn" ) {
            return next()
        }
        return next({ name: "Dashboard" })
    }
    if (to.name !== "SignIn" ) {
        return ({ name: "SignIn" })
    }
    return next()
})

export default router
