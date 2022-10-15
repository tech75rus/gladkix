import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {
      title: 'Home'
    },
  },
  {
    path: '/technologies',
    name: 'technologies',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: function () {
      return import(/* webpackChunkName: "about" */ '../views/TechnologiesView')
    },
    meta: {
      title: 'Technologies'
    },
  },
  {
    path: '/contact',
    name: 'contact',
    component: function () {
      return import('../views/ContactView')
    },
    meta: {
      title: 'Contact'
    },
  },
  {
    path: '/works',
    name: 'works',
    component: function () {
      return import('../views/WorkView')
    },
    meta: {
      title: 'Works'
    },
  },
  {
    path: '/article/:id',
    name: 'article',
    component: function () {
      return import('../views/ArticleView')
    },
    meta: {
      title: 'article'
    },
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  document.title = `${to.meta.title}`
  next();
});

export default router
