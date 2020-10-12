<template>
  <v-card-text :class="['card-side', type]">
    <template v-if="type === 'study'">
      <p class="description">{{ description }}</p>
      <h2 class="word">{{ word }}</h2>
      <p class="sample">{{ sample }}</p>
    </template>
    <template v-else-if="type === 'hidden'"> </template>
    <template v-else-if="type === 'question'">
      <v-text-field
        v-model="response"
        full-width
        height="100"
        outlined
        autofocus
        @keydown.enter="enter"
      />
    </template>
    <template v-else-if="type === 'result'">
      <card-result :word="word" :sample="sample" :response="response" />
    </template>
  </v-card-text>
</template>
<script>
import CardResult from '~/components/Card/CardResult'

function enter() {
  this.$store.dispatch('mode/solve', { response: this.response })
}

export default {
  name: 'CardSide',
  components: { CardResult },
  props: {
    word: {
      type: String,
      required: true,
    },
    description: {
      type: String,
    },
    sample: {
      type: String,
    },
    type: {
      type: String,
      validator(value) {
        return ['study', 'hidden', 'question', 'result'].includes(value)
      },
      default() {
        return 'study'
      },
    },
  },
  data() {
    return {
      response: null,
    }
  },
  watch: {
    word() {
      this.response = null
    },
  },
  methods: {
    enter,
  },
}
</script>

<style scoped lang="scss">
.card-side {
  flex: 1 1 auto;
  margin: 1em;
  padding-right: 3em;
  height: 50%;
  .description {
    color: #00a7d0;
    font-size: 2em;
    font-weight: 400;
    line-height: 1em;
  }
  .word {
    margin-top: 0.5em;
    font-size: 4em;
    line-height: 1.1em;
    font-weight: 300;
    font-family: 'Roboto', sans-serif;
    text-transform: lowercase;
  }

  .sample {
    margin-top: 1em;
    font-style: italic;
    color: grey;
    font-size: 1.3rem;
    line-height: 1.1em;
    letter-spacing: 0;
  }

  &.hidden {
    background-color: darkgray;
    opacity: 0.1;
    margin: 0;
  }
}
</style>
