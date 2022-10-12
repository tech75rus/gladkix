import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Registration from "@/views/Registration";
import Login from "@/views/Login";

const routes = [
  {
    path: '/',
    name: 'Home',
    meta: {
      layout: 'main',
    },
    component: HomeView
  },
  {
    path: '/about',
    name: 'About',
    meta: {
      layout: 'main',
    },
    component: () => import('../views/AboutView')
  },
  {
    path: '/registration',
    name: 'Registration',
    meta: {
      layout: 'empty',
    },
    component: Registration
  },
  {
    path: '/login',
    name: 'Login',
    meta: {
      layout: 'empty',
    },
    component: Login
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
