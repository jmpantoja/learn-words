<template>
  <div>
    <div class="result">
      <span
        v-for="(item, index) in result"
        v-show="item[0] != -1"
        :key="index"
        class="display-3"
        :style="{ color: color[item[0]] }"
      >
        {{ item[1] }}
      </span>
    </div>
    <div v-if="error" class="result">
      <span
        v-for="(item, index) in result"
        v-show="item[0] != 1"
        :key="index"
        class="display-3"
        :style="{ color: color[item[0]] }"
      >
        {{ item[1] }}
      </span>
    </div>
  </div>
</template>

<script>
const diff = require('fast-diff')
export default {
  props: {
    word: {
      type: String,
    },
    answer: {
      type: String,
    },
  },
  data() {
    return {
      color: {
        '-1': 'red',
        0: 'green',
        1: 'grey',
      },
    }
  },
  computed: {
    result() {
      const answer = this.answer ? this.answer : ''
      return diff(answer, this.word)
    },
    error() {
      const diff = this.result.filter((item) => item[0] !== 0)
      return diff.length > 0
    },
  },
}
</script>
<style>
.face .v-input input {
  max-height: 96px !important;
}
</style>
