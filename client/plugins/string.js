import Vue from 'vue'

Vue.mixin({
  methods: {
    is_empty_string(value) {
      return value === null || !/\S/.test(value)
    },
  },
})
