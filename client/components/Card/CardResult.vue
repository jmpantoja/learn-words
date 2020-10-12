<template>
  <div class="result">
    <div class="line">
      <span class="equal">
        {{ word }}
      </span>
      <p class="sample">{{ sample }}</p>
    </div>

    <div v-if="error" class="line">
      <span v-for="(item, index) in result" :key="index" :class="item.style">
        {{ item.value }}
      </span>
    </div>
  </div>
</template>
<script>
const diff = require('fast-diff')

function style(index) {
  switch (index) {
    case 0:
      return 'equal'
    case 1:
      return 'insert'
    case -1:
      return 'delete'
  }
}

function text(value, index) {
  if (index === 0) {
    return value
  }

  if (value.match(/^\s+$/)) {
    return '_'
  }
  return value
}

function normalize(value) {
  return value.trim().split(/\s+/).join(' ')
}

export default {
  name: 'CardResult',
  props: {
    word: {
      type: String,
      required: true,
    },
    sample: {
      type: String,
    },
    response: {
      type: String,
      required: true,
    },
  },
  computed: {
    result() {
      const answer = this.response ? this.response : ''
      const difference = diff(normalize(answer), this.word)
      return difference.map(function (item) {
        return {
          equal: item[0] === 0,
          style: style(item[0]),
          value: text(item[1], item[0]),
        }
      })
    },
    error() {
      const diff = this.result.filter((item) => item.equal === false)
      return diff.length > 0
    },
  },
}
</script>

<style lang="scss" scoped>
.result {
  .line {
    margin-bottom: 0.5em;
    font-size: 4em;
    font-weight: 300;

    .equal {
      color: green;
    }
    .insert {
      color: gray;
    }
    .delete {
      color: red;
      text-decoration: grey line-through;
    }

    .sample {
      margin-top: 2em;
      font-style: italic;
      color: grey;
      font-weight: 400;
      font-size: 1.3rem;
      line-height: 1.1em;
      letter-spacing: 0;
    }
  }
}
</style>
