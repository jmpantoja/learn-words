<template>
  <div class="face">
    <v-text-field
      v-if="showInput"
      v-model="answer"
      outlined
      autofocus
      class="display-3"
      @keyup.enter="enter"
    />

    <question-result v-if="!showInput" :answer="computed_answer" :word="word"/>
  </div>
</template>

<script>
export default {
  props: {
    word: {
      type: String,
    },
    value: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      answer: '',
      resolved: false,
    }
  },
  computed: {
    showInput() {
      if (this.value === null && this.resolved !== true) {
        return true
      }
      return false
    },
    computed_answer() {
      if (this.value === null) {
        return this.answer
      }
      return this.value
    },
  },
  created() {
    window.addEventListener('keyup', this.keyup)
  },
  destroyed() {
    window.removeEventListener('keyup', this.keyup)
  },
  methods: {
    reset() {
      this.resolved = false
      this.answer = null
    },
    enter(event) {
      event.stopPropagation()
      if (this.resolved === true) {
        return
      }
      this.resolved = true
      this.$emit('resolve', this.answer)
    },
    keyup(event) {
      if (this.resolved === false) {
        return
      }
      const isEnter = ['NumpadEnter', 'Enter'].includes(event.code)
      if (!isEnter) {
        return
      }

      this.reset()
      this.$emit('checked', this.answer)
    },
  },
}
</script>

<style>
.face .v-input input {
  max-height: 96px !important;
}

.face p.result .span {
  display: inline-block;
}
</style>
