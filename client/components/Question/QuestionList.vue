<template>
  <v-card class="'mx-auto'" width="700" height="550" elevation="4">
    <v-overlay :value="loading" absolute opacity="0.1">
      <v-progress-circular indeterminate size="64" color="primary" />
    </v-overlay>
    <template v-if="empty">
      <v-card-text class="empty-list">
        <div class="full-face">
          <p class="display-2">No results found for the search</p>

          <p class="sample">
            <nuxt-link to="/"> Try again</nuxt-link>
          </p>
        </div>
      </v-card-text>
    </template>

    <template v-if="word">
      <v-card-actions>
        <question-toolbar
          ref="toolbar"
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

      <v-footer v-if="!finished && total > 0" absolute>
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
import review from '@/apollo/queries/daily_review'
import resolve from '@/apollo/mutation/solve_question'

export default {
  props: {
    tags: {
      type: Array,
      default() {
        return []
      },
    },
    num: {
      type: Number,
      default() {
        return 10
      },
    },
    level: {
      type: Number,
      default() {
        return 1
      },
    },
    review: {
      type: Boolean,
    },
    training: {
      type: Boolean,
    },
  },
  data() {
    return {
      items: [],
      index: 0,
      empty: false,
      resume: {
        success: 0,
        error: 0,
        values: {},
      },
    }
  },
  computed: {
    loading() {
      return this.empty === false && this.word === undefined
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
      query() {
        return this.review ? review : questions
      },
      variables() {
        return {
          userId: '28408286-0245-11eb-98fa-0242ac130007',
          tags: this.tags,
          limit: this.num,
          level: this.level,
        }
      },
      update(data) {
        this.init(data.questions_by_tag || data.daily_review)
      },
    },
  },
  methods: {
    init(data) {
      const total = Array.from(data).length
      if (total === 0) {
        this.empty = true
        return
      }
      this.items = data
      this.index = 0
    },
    goTo(index) {
      this.$refs.card.reset()
      this.index = index
    },
    save(success) {
      this.$apollo.mutate({
        mutation: resolve,
        variables: {
          input: {
            user: '28408286-0245-11eb-98fa-0242ac130007',
            question: this.word.id,
            successful: success,
          },
        },
        update(store, data) {
          console.log(data.data.solve_question)
        },
      })
    },
    success() {
      this.resume.success++
      this.resume.values[this.index] = this.word.word
      this.$refs.toolbar.play()
      this.save(true)
    },
    error(answer) {
      this.resume.error++
      this.resume.values[this.index] = answer || ''
      this.$refs.toolbar.play()
      this.save(false)
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

.empty-list {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
</style>
