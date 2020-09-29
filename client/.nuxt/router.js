import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _67769f18 = () => interopDefault(import('../pages/config.vue' /* webpackChunkName: "pages/config" */))
const _156494f5 = () => interopDefault(import('../pages/exam.vue' /* webpackChunkName: "pages/exam" */))
const _2a0899e3 = () => interopDefault(import('../pages/study.vue' /* webpackChunkName: "pages/study" */))
const _6e72086c = () => interopDefault(import('../pages/index.vue' /* webpackChunkName: "pages/index" */))

// TODO: remove in Nuxt 3
const emptyFn = () => {}
const originalPush = Router.prototype.push
Router.prototype.push = function push (location, onComplete = emptyFn, onAbort) {
  return originalPush.call(this, location, onComplete, onAbort)
}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: decodeURI('/'),
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/config",
    component: _67769f18,
    name: "config"
  }, {
    path: "/exam",
    component: _156494f5,
    name: "exam"
  }, {
    path: "/study",
    component: _2a0899e3,
    name: "study"
  }, {
    path: "/",
    component: _6e72086c,
    name: "index"
  }],

  fallback: false
}

export function createRouter () {
  return new Router(routerOptions)
}