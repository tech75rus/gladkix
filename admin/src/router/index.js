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
    path: '/add-article',
    name: 'AddArticle',
    meta: {
      layout: 'main',
    },
    component: () => import('../views/AddArticleView')
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
  {
    path: '/technology-tag',
    name: 'TechnologyTag',
    meta: {
      layout: 'main',
    },
    component: () => import('../views/TechnologyTagView')
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
