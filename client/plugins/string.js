import Vue from 'vue'

const _ = require('underscore')

Vue.mixin({
  data() {
    return {
      pepe: 'hola',
      required_rule: [
        function (value) {
          return !_.isEmpty(value) || "This field can't be empty"
        },
      ],
    }
  },
})
