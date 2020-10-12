const tags = require('~/apollo/queries/all_tags')

export const state = () => ({
  all: [],
})

export const mutations = {
  load(state, tags) {
    tags.forEach(function (tag) {
      state.all.push(tag.tag)
    })
  },
}

export const actions = {
  load(context) {
    if (context.state.all.length > 0) return
    const client = this.app.apolloProvider.defaultClient
    client
      .query({
        query: tags,
      })
      .then(({ data }) => {
        context.commit('load', data.all_tags)
      })
  },
}
