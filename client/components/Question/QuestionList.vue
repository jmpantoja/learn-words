<template>
  <v-card class="'mx-auto'" width="700" height="500" elevation="4">
    <v-overlay :value="loading" absolute opacity="0.1">
      <v-progress-circular indeterminate size="64" color="primary" />
    </v-overlay>
    <template v-if="word">
      <v-card-actions>

        <question-toolbar
          :word="word"
          :resume="training ? null : resume"
          :favourite="!finished"
          :reply="finished"
          :speaker="training"
        />
      </v-card-actions>

      <v-card-text v-if="!finished" class="question-card">
        <question-card
          ref="card"
          :training="training"
          :word="word"
          :value="resume.values[index]"
          @input="$listeners"
          @success="success"
          @error="error"
          @readyForNext="next"
        />
      </v-card-text>

      <question-mark
        v-if="finished"
        :success="resume.success"
        :error="resume.error"
      />

      <v-footer v-if="!finished && total > 1" absolute>
        <question-navigation
          ref="navigation"
          :index="index"
          :total="total"
          :resume="training ? null : resume"
          @goTo="goTo"
        />
      </v-footer>
    </template>
  </v-card>
</template>

<script>
import questions from '@/apollo/queries/questions_by_tag'

export default {
  props: {
    tags: {
      type: Array,
      required: true,
    },
    num: {
      type: Number,
      required: true,
    },
    training: {
      type: Boolean,
    },
  },
  data() {
    return {
      items: [],
      index: 0,
      resume: {
        success: 0,
        error: 0,
        values: {},
      },
    }
  },
  computed: {
    loading() {
      return this.word === undefined
    },
    word() {
      return this.items[this.index]
    },
    total() {
      return this.items.length - 1
    },
    finished() {
      const remaining = this.total - (this.resume.success + this.resume.error)
      return remaining < 0
    },
  },
  apollo: {
    questions: {
      prefetch: true,
      query: questions,
      variables() {
        return {
          tags: this.tags,
          num: this.num,
        }
      },
      update(data) {
        this.init(data.questions_by_tag)
      },
    },
  },
  methods: {
    init(data) {
      this.items = data
      this.index = 0
    },
    goTo(index) {
      this.$refs.card.reset()
      this.index = index
    },
    success() {
      this.resume.success++
      this.resume.values[this.index] = this.word.word
      console.log('success', this.index)
    },
    error(answer) {
      this.resume.error++
      this.resume.values[this.index] = answer || ''
      console.log('error', answer, this.index)
    },
    next() {
      this.$refs.navigation.next()
    },
  },
}
</script>

<style scoped>
.question-card {
  height: 70%;
  padding: 0;
}
</style>
