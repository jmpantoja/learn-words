export const state = () => ({
  mode: 'study',
  type: 'study',
  mute: false,
})

export const getters = {
  mode(state) {
    return state.mode
  },
  type(state) {
    return state.type
  },
  mute(state) {
    return state.mute
  },
}

export const mutations = {
  init(state, mode) {
    state.mode = mode
    state.type = mode
  },
  reset(state) {
    state.type = state.mode
  },
  toggle_show_answer(state) {
    if (state.type === 'study') {
      state.type = 'hidden'
    } else if (state.type === 'hidden') {
      state.type = 'study'
    }
  },
  toggle_show_result(state) {
    if (state.type === 'question') {
      state.type = 'result'
    } else if (state.type === 'result') {
      state.type = 'question'
    }
  },
  toggle_mute(state) {
    state.mute = !state.mute
  },
}

export const actions = {
  advance(context) {
    const type = context.state.type
    switch (type) {
      case 'study':
        context.dispatch('questions/next', {}, { root: true })
        break
      case 'hidden':
        context.commit('toggle_show_answer')
        break
      case 'question':
        context.commit('toggle_show_result')
        break
      case 'result':
        context.commit('toggle_show_result')
        context.dispatch('questions/next', {}, { root: true })
        break
    }
  },
  solve(context, { response }) {
    const question = context.rootGetters['questions/current']

    const payload = {
      questionId: question.id,
      response,
    }

    context.dispatch('questions/solve', payload, { root: true })
    context.dispatch('advance')
  },
}
