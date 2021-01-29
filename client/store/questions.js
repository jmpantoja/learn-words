const questions = require('~/apollo/queries/questions_by_tag.gql')
const exam = require('~/apollo/queries/exam.gql')
const solve = require('~/apollo/mutation/solve_question.gql')

export const state = () => ({
  loading: true,
  index: 0,
  finished: false,
  exam: null,
  pack: [],
})

export const getters = {
  isLoading(state) {
    return state.loading
  },
  isEmpty(state) {
    return state.pack.length === 0
  },
  isFirst(state) {
    return state.index === 0
  },
  isLast(state) {
    return state.index === state.pack.length - 1
  },
  current(state) {
    const index = state.index
    const question = state.pack[index]
    if (typeof question !== 'object') {
      return null
    }
    return question
  },
  position(state) {
    return state.index + 1
  },
  length(state) {
    return state.pack.length
  },
  finished(state) {
    return state.finished
  },
}

export const mutations = {
  finished(state, finished) {
    state.finished = finished
  },
  exam(state, exam) {
    state.exam = exam
  },
  first(state) {
    state.index = 0
  },
  previous(state) {
    if (state.index <= 0) {
      return
    }
    state.index--
  },
  next(state) {
    if (state.index >= state.pack.length - 1) {
      return
    }
    state.index++
  },
  last(state) {
    state.index = state.pack.length - 1
  },
  load(state, questions) {
    state.pack.push(...questions)
    state.index = 0
    state.loading = false
  },
  clear(state) {
    state.finished = false
    state.loading = true
    state.pack = []
  },
}

export const actions = {
  first(context) {
    context.commit('finished', false)
    context.commit('mode/reset', {}, { root: true })
    context.commit('first')
  },
  previous(context) {
    context.commit('finished', false)
    context.commit('mode/reset', {}, { root: true })
    context.commit('previous')
  },
  next(context) {
    context.commit('finished', context.getters.isLast)
    context.commit('mode/reset', {}, { root: true })
    context.commit('next')
  },
  last(context) {
    context.commit('finished', false)
    context.commit('mode/reset', {}, { root: true })
    context.commit('last')
  },
  load(context, { mode, category, level, size }) {
    const client = this.app.apolloProvider.defaultClient
    context.commit('clear')
    client
      .query({
        query: questions,
        variables: {
          userId: '28408286-0245-11eb-98fa-0242ac130007',
          tags: category,
          limit: size,
          level,
        },
      })
      .then(({ data }) => {
        context.commit('load', data.questions_by_tag)
        context.commit('mode/init', mode, { root: true })
        context.commit('exam', null)
      })
  },
  exam(context, { type, size, category }) {
    const client = this.app.apolloProvider.defaultClient
    context.commit('clear')
    client
      .query({
        query: exam,
        variables: {
          userId: '28408286-0245-11eb-98fa-0242ac130007',
          limit: size,
          type,
          tags: category,
        },
      })
      .then(({ data }) => {
        context.commit('load', data.exam)
        context.commit('mode/init', 'question', { root: true })
        context.commit('exam', type)
      })
  },
  solve(context, { questionId, response }) {
    const type = context.rootGetters['mode/type']

    if (!['question', 'study'].includes(type)) {
      return
    }

    const dryRun = ['failed'].includes(context.state.exam)

    const client = this.app.apolloProvider.defaultClient
    client.mutate({
      mutation: solve,
      variables: {
        input: {
          user: '28408286-0245-11eb-98fa-0242ac130007',
          question: questionId,
          response,
          dryRun,
        },
      },
    })
  },
}
