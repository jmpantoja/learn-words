export { default as ConfigCards } from '../../components/ConfigCards.vue'
export { default as LayoutCenter } from '../../components/LayoutCenter.vue'
export { default as QuestionAnswer } from '../../components/Question/QuestionAnswer.vue'
export { default as QuestionCard } from '../../components/Question/QuestionCard.vue'
export { default as QuestionFavourite } from '../../components/Question/QuestionFavourite.vue'
export { default as QuestionList } from '../../components/Question/QuestionList.vue'
export { default as QuestionMark } from '../../components/Question/QuestionMark.vue'
export { default as QuestionNavigation } from '../../components/Question/QuestionNavigation.vue'
export { default as QuestionReply } from '../../components/Question/QuestionReply.vue'
export { default as QuestionResult } from '../../components/Question/QuestionResult.vue'
export { default as QuestionSpeaker } from '../../components/Question/QuestionSpeaker.vue'
export { default as QuestionToolbar } from '../../components/Question/QuestionToolbar.vue'
export { default as QuestionWording } from '../../components/Question/QuestionWording.vue'

export const LazyConfigCards = import('../../components/ConfigCards.vue' /* webpackChunkName: "components/ConfigCards" */).then(c => c.default || c)
export const LazyLayoutCenter = import('../../components/LayoutCenter.vue' /* webpackChunkName: "components/LayoutCenter" */).then(c => c.default || c)
export const LazyQuestionAnswer = import('../../components/Question/QuestionAnswer.vue' /* webpackChunkName: "components/Question/QuestionAnswer" */).then(c => c.default || c)
export const LazyQuestionCard = import('../../components/Question/QuestionCard.vue' /* webpackChunkName: "components/Question/QuestionCard" */).then(c => c.default || c)
export const LazyQuestionFavourite = import('../../components/Question/QuestionFavourite.vue' /* webpackChunkName: "components/Question/QuestionFavourite" */).then(c => c.default || c)
export const LazyQuestionList = import('../../components/Question/QuestionList.vue' /* webpackChunkName: "components/Question/QuestionList" */).then(c => c.default || c)
export const LazyQuestionMark = import('../../components/Question/QuestionMark.vue' /* webpackChunkName: "components/Question/QuestionMark" */).then(c => c.default || c)
export const LazyQuestionNavigation = import('../../components/Question/QuestionNavigation.vue' /* webpackChunkName: "components/Question/QuestionNavigation" */).then(c => c.default || c)
export const LazyQuestionReply = import('../../components/Question/QuestionReply.vue' /* webpackChunkName: "components/Question/QuestionReply" */).then(c => c.default || c)
export const LazyQuestionResult = import('../../components/Question/QuestionResult.vue' /* webpackChunkName: "components/Question/QuestionResult" */).then(c => c.default || c)
export const LazyQuestionSpeaker = import('../../components/Question/QuestionSpeaker.vue' /* webpackChunkName: "components/Question/QuestionSpeaker" */).then(c => c.default || c)
export const LazyQuestionToolbar = import('../../components/Question/QuestionToolbar.vue' /* webpackChunkName: "components/Question/QuestionToolbar" */).then(c => c.default || c)
export const LazyQuestionWording = import('../../components/Question/QuestionWording.vue' /* webpackChunkName: "components/Question/QuestionWording" */).then(c => c.default || c)
