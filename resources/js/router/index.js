import Vue from 'vue'
import VueRouter from 'vue-router'
import {next} from "lodash/seq";

Vue.use(VueRouter)

const routes = [
    {
        path : '/',
        component: () => import('../pages/LoginPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth') == undefined)
            {
                next()
            }else{
                next('/home')
            }
        },
    },
    {
        path : '/request-password',
        component: () => import('../pages/RequestPasswordPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth') == undefined)
            {
                next()
            }else{
                next('/home')
            }
        },
    },
    {
        path : '/reset',
        component: () => import('../pages/ResetPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth') == undefined)
            {
                next()
            }else{
                next('/home')
            }
        },
    },
    {
        path : '/home',
        component: () => import('../pages/DashboardPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth'))
            {
                next()
            }else{
                next('/')
            }
        },
        children : [
            {
                path : '/',
                component : () => import('../components/MainComponent')
            },
            {
                path : 'users',
                component : () => import('../pages/User/UserPage'),
                children : [
                    {
                        path : '/',
                        component : () => import('../pages/User/datatableUserPage'),
                    },
                    {
                        path : 'add-user',
                        component : () => import('../pages/User/AddUserPage')
                    },
                    {
                        path : 'change-password-user/:id',
                        component : () => import('../pages/User/ChangePasswordUserPage')
                    },
                    {
                        path: 'update-user/:id',
                        name : 'updateUser',
                        props : true,
                        component : () => import('../pages/User/UpdateUserPage')
                    }
                ]
            }
        ]
    }

]



export default new VueRouter({
    mode: 'history',
    routes
})

