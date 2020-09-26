<template>
  <div class="question-card-inner">
    <question-wording
      :word="word.wording"
      :sample="word.translation"
      :description="word.description"
      :display-sample="training"
    />
    <v-divider />
    <template v-if="training">
      <question-wording
        :word="word.word"
        :sample="word.sample"
        :display-sample="training"
      />
    </template>
    <template v-else>
      <question-answer
        ref="answer"
        :value="value"
        :word="word.word"
        @resolve="resolve"
        @checked="checked"
      />
    </template>
  </div>
</template>
<script>
export default {
  props: {
    value: {
      type: String,
      default: null,
    },
    training: {
      type: Boolean,
    },
    word: {},
  },
  methods: {
    reset() {
      if (this.$refs.answer) {
        this.$refs.answer.reset()
      }
    },
    resolve(answer) {
      if (this.word.word === answer) {
        this.$emit('success')
      } else {
        this.$emit('error', answer)
      }
    },
    checked() {
      this.$emit('readyForNext')
    },
  },
}
</script>

<style>
.question-card-inner {
  height: 100%;
  padding: 0em;
}

.question-card .face {
  height: 50%;
  margin: 0;
  padding: 1em 2em;
  display: flex;
  flex-direction: column;
  justify-content: start;
}

.question-card .face p {
  display: block;
}
</style>
