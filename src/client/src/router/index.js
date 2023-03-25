import { createRouter, createWebHistory, RouterView } from 'vue-router'
import { useAuthStore } from '@/stores/auth.js'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            redirect: '/login'
        },
        /* -- LOGIN -- */
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/login/Mediator.vue'),
            meta: {
                title: 'Login'
            },
            children: [
                {
                    path: '',
                    name: 'LoginIndex',
                    component: () => import('../views/login/Index.vue'),
                    meta: {
                        subtitle: ''
                    }
                }
            ]
        },
        /* -- DASHBOARD -- */
        {
            path: '/dashboard',
            name: 'dashboard',
            component: () => import('../views/dashboard/Mediator.vue'),
            meta: {
                title: 'Dashboard'
            },
            redirect: {
                name: "DashboardIndex"
            },
            children: [
                {
                    path: 'index',
                    name: 'DashboardIndex',
                    component: () => import('../views/dashboard/Index.vue'),
                    meta: {
                        subtitle: ''
                    }
                }
            ]
        },
        /* -- POST -- */
        {
            path: '/posts',
            name: 'post',
            component: () => import('../views/post/Mediator.vue'),
            meta: {
                title: 'Post'
            },
            props: {
                goBack: true
            },
            redirect: {
                name: "postIndex"
            },
            children: [
                {
                    path: 'index',
                    name: 'postIndex',
                    component: () => import('../views/post/Index.vue'),
                    meta: {
                        subtitle: ''
                    }
                },
                {
                    path: 'insert',
                    name: 'postInsert',
                    component: () => import('../views/post/Insert.vue'),
                    meta: {
                        subtitle: 'Inserir',
                        goBack: true
                    }
                },
                {
                    path: 'show/:id',
                    name: 'postShow', component: () => import('../views/post/Show.vue'),
                    meta: {
                        subtitle: 'Visualizar',
                        goBack: true
                    }
                },
                {
                    path: 'update/:id',
                    name: 'postUpdate',
                    component: () => import('../views/post/Show.vue'),
                    meta:
                    {
                        subtitle: 'Editar',
                        goBack: true
                    }
                },
            ]
        },
        /* -- FILE -- */
        {
            path: '/files',
            name: 'file',
            component: () =>import('../views/file/Mediator.vue'),
            meta: {
                title: 'File'
            }, 
            redirect: {
                name: "fileIndex"
            },
            children: [
                {
                    path: 'index',
                    name: 'fileIndex',
                    component: () => import('../views/file/Index.vue'),
                    meta: {
                        subtitle: ''
                    }
                },
                {
                    path: 'insert',
                    name: 'fileInsert',
                    component: () => import('../views/file/Insert.vue'),
                    meta: {
                        subtitle: 'Inserir',
                        goBack: true
                    }
                },
                {
                    path: 'show/:id',
                    name: 'fileShow',
                    component: () => import('../views/file/Show.vue'),
                    meta: {
                        subtitle: 'Visualizar',
                        goBack: true
                    }
                },
            ]
        },
        /* -- ACCOUNT -- */
        {
            path: '/account',
            name: 'account',
            component: () => import('../views/account/Mediator.vue'),
            meta: {
                title: 'Conta'
            }, 
            redirect: {
                name: "accountBasicInfo"
            },
            children: [
                {
                    path: 'basic-info',
                    name: 'accountBasicInfo',
                    component: () => import('../views/account/BasicInfo.vue'),
                    meta: {
                        subtitle: 'Informações básicas',
                        goBack: true
                    }
                },
                {
                    path: 'profile-picture',
                    name: 'accountProfilePicture',
                    component: () => import('../views/account/ProfilePicture.vue'),
                    meta: {
                        subtitle: 'Foto do perfil',
                        goBack: true
                    }
                },
                {
                    path: 'credential',
                    name: 'accountCredential',
                    component: () => import('../views/account/Credential.vue'),
                    meta: {
                        subtitle: 'Credenciais',
                        goBack: true
                    }
                },
            ]
        }
    ]
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    if (!authStore.auth.isLogged && to.name !== 'LoginIndex') next({ name: 'LoginIndex' })
    else if (authStore.auth.isLogged && to.name == 'LoginIndex') next({ name: 'DashboardIndex' })
    else next()

    document.title = `${to.meta.title} ${to.meta.subtitle}`;
})

export default router