import { createRouter, createWebHistory, RouterView } from 'vue-router'
import { useAuthStore } from "@/stores/authStore"


import SigninView from '@/views/Authentication/SigninView.vue'
// import SignupView from '@/views/Authentication/SignupView.vue'
// import CalendarView from '@/views/CalendarView.vue'


import DashboardLayout from '@/layouts/DashboardLayout.vue'
import GenericError from '@/views/Errors/GenericError.vue'


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
        path: "/error/404",
        component: GenericError,
        name: "404",
        meta: {
            title: 'Page Not Found',
            code: '404',
            message: "Sorry, the page you're looking for doesn't exist or has been moved."
        },
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
                path: "dashboard",
                component: () => import("@/views/Dashboard/MainDashboard.vue"),
                name: "Dashboard",
                meta: {
                    title: 'Dashboard'
                },

            },
            {
                path: "messaging",
                component: () => import("@/views/Messaging/IndexMessaging.vue"),
                name: "Messaging",
                meta: {
                    title: 'Mesaging App'
                },

            },
            {
                path: 'users',
                name: 'users',
                component: RouterView,
                redirect: "/users/index",
                meta: {
                    title: 'Users'
                },
                children: [
                    {
                        path: 'index',
                        name: 'IndexUser',
                        component: () => import("@/views/User/IndexUser.vue"),
                        meta: {
                            layout: 'DashboardLayout',
                            title: 'Users Index'
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
                path: 'documents',
                name: 'documents',
                component: RouterView,
                redirect: "/documents/index",
                meta: {
                    title: 'Documents'
                },
                children: [
                    {
                        path: "index",
                        component: () => import("@/views/Dashboard/DocumentDashboard.vue"),
                        name: "IndexDocument",
                        meta: {
                            title: 'Documents Dashboard'
                        }
                    },
                    {
                        path: 'institutions',
                        name: 'institutions',
                        component: RouterView,
                        redirect: "/documents/institutions/index",
                        children: [
                            {
                                path: 'index',
                                name: 'IndexInstitution',
                                component: () => import("@/views/Docs/Institution/IndexInstitution.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Document Institutions Index'
                                }
                            },
                            {
                                path: 'create',
                                name: 'CreateInstitution',
                                component: () => import("@/views/Docs/Institution/CreateInstitution.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Create Document Institutions'
                                }
                            },
                            {
                                path: 'edit/:id',
                                name: 'EditInstitution',
                                component: () => import("@/views/Docs/Institution/EditInstitution.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Edit Document Institutions'
                                }
                            }
                        ]
                    },
                    {
                        path: 'classifications',
                        name: 'classifications',
                        component: RouterView,
                        redirect: "/documents/classifications/index",
                        children: [
                            {
                                path: 'index',
                                name: 'IndexClassification',
                                component: () => import("@/views/Docs/Classification/IndexClassification.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Document Classifications Index'
                                }
                            },
                            {
                                path: 'create',
                                name: 'CreateClassification',
                                component: () => import("@/views/Docs/Classification/CreateClassification.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Create Document Classifications'
                                }
                            },
                            {
                                path: 'edit/:id',
                                name: 'EditClassification',
                                component: () => import("@/views/Docs/Classification/EditClassification.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Edit Document Classifications'
                                }
                            }
                        ]
                    },
                    {
                        path: 'letters',
                        name: 'letters',
                        component: RouterView,
                        redirect: "/documents/letters/index",
                        children: [
                            {
                                path: 'index',
                                name: 'IndexLetter',
                                component: () => import("@/views/Docs/Letter/IndexLetter.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Document Letters Index'
                                }
                            },
                            {
                                path: 'create',
                                name: 'CreateLetter',
                                component: () => import("@/views/Docs/Letter/CreateLetter.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Create Document Letters'
                                }
                            },
                            {
                                path: 'edit/:id',
                                name: 'EditLetter',
                                component: () => import("@/views/Docs/Letter/EditLetter.vue"),
                                meta: {
                                    layout: 'DashboardLayout',
                                    title: 'Edit Document Letters'
                                }
                            }
                        ]
                    },
                ]
            },
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/error/404',
    },
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
    if (authStore.isAuthenticated) {
        if (to.name !== "SignIn") {
            return next()
        }
        return next({ name: "Dashboard" })
    }
    if (to.name !== "SignIn") {
        return ({ name: "SignIn" })
    }
    return next()
})

export default router
