const stats = require('~/apollo/queries/stats')

export const state = function () {
  return {
    all: [],
  }
}

export const getters = {
  data(state) {
    return state.all.map((stat) => {
      return [
        stat.date,
        stat.eachDay,
        stat.eachThreeDays,
        stat.eachWeek,
        stat.eachTwoWeeks,
        stat.eachMonth,
      ]
    })
  },
}

export const mutations = {
  load(state, data) {
    state.all = data
  },
}

export const actions = {
  load(context) {
    if (context.state.all.length > 0) return
    const client = this.app.apolloProvider.defaultClient
    client
      .query({
        query: stats,
        variables: {
          userId: '28408286-0245-11eb-98fa-0242ac130007',
        },
      })
      .then(({ data }) => {
        context.commit('load', data.stats_by_user)
      })
  },
}
