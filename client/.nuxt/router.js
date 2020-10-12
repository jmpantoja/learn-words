import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _2304f5b9 = () => interopDefault(import('../pages/stats.vue' /* webpackChunkName: "pages/stats" */))
const _77019ec2 = () => interopDefault(import('../pages/exam/_type/index.vue' /* webpackChunkName: "pages/exam/_type/index" */))
const _4df87b5a = () => interopDefault(import('../pages/exam/_type/_size/index.vue' /* webpackChunkName: "pages/exam/_type/_size/index" */))
const _708a4d3a = () => interopDefault(import('../pages/cards/_mode/_category/_level/_size/index.vue' /* webpackChunkName: "pages/cards/_mode/_category/_level/_size/index" */))
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
    path: "/stats",
    component: _2304f5b9,
    name: "stats"
  }, {
    path: "/exam/:type",
    component: _77019ec2,
    name: "exam-type"
  }, {
    path: "/exam/:type?/:size",
    component: _4df87b5a,
    name: "exam-type-size"
  }, {
    path: "/cards/:mode?/:category?/:level?/:size",
    component: _708a4d3a,
    name: "cards-mode-category-level-size"
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
