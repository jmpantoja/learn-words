export { default as BackButton } from '../../components/BackButton.vue'
export { default as CardConfig } from '../../components/CardConfig.vue'
export { default as CardPage } from '../../components/CardPage.vue'
export { default as CardBody } from '../../components/Card/CardBody.vue'
export { default as CardNavigation } from '../../components/Card/CardNavigation.vue'
export { default as CardResult } from '../../components/Card/CardResult.vue'
export { default as CardSide } from '../../components/Card/CardSide.vue'
export { default as CardToolbar } from '../../components/Card/CardToolbar.vue'
export { default as CardWrapper } from '../../components/Card/CardWrapper.vue'
export { default as BtnShowAnswer } from '../../components/Toolbar/BtnShowAnswer.vue'
export { default as BtnSpeaker } from '../../components/Toolbar/BtnSpeaker.vue'

export const LazyBackButton = import('../../components/BackButton.vue' /* webpackChunkName: "components/BackButton" */).then(c => c.default || c)
export const LazyCardConfig = import('../../components/CardConfig.vue' /* webpackChunkName: "components/CardConfig" */).then(c => c.default || c)
export const LazyCardPage = import('../../components/CardPage.vue' /* webpackChunkName: "components/CardPage" */).then(c => c.default || c)
export const LazyCardBody = import('../../components/Card/CardBody.vue' /* webpackChunkName: "components/Card/CardBody" */).then(c => c.default || c)
export const LazyCardNavigation = import('../../components/Card/CardNavigation.vue' /* webpackChunkName: "components/Card/CardNavigation" */).then(c => c.default || c)
export const LazyCardResult = import('../../components/Card/CardResult.vue' /* webpackChunkName: "components/Card/CardResult" */).then(c => c.default || c)
export const LazyCardSide = import('../../components/Card/CardSide.vue' /* webpackChunkName: "components/Card/CardSide" */).then(c => c.default || c)
export const LazyCardToolbar = import('../../components/Card/CardToolbar.vue' /* webpackChunkName: "components/Card/CardToolbar" */).then(c => c.default || c)
export const LazyCardWrapper = import('../../components/Card/CardWrapper.vue' /* webpackChunkName: "components/Card/CardWrapper" */).then(c => c.default || c)
export const LazyBtnShowAnswer = import('../../components/Toolbar/BtnShowAnswer.vue' /* webpackChunkName: "components/Toolbar/BtnShowAnswer" */).then(c => c.default || c)
export const LazyBtnSpeaker = import('../../components/Toolbar/BtnSpeaker.vue' /* webpackChunkName: "components/Toolbar/BtnSpeaker" */).then(c => c.default || c)
