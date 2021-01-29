<template>
  <v-card
    class="config mx-auto d-flex flex-column"
    width="850"
    height="620"
    elevation="10"
  >
    <v-form ref="form" class="d-flex">
      <div class="mode">
        <v-radio-group v-model="mode" class="radio-group" label="Learn it!">
          <v-radio
            v-for="(radio, key) in modes.study"
            :key="key"
            :label="radio.label"
            :value="key"
            class="radio"
            color="primary"
          />
        </v-radio-group>

        <v-radio-group v-model="mode" class="radio-group" label="Take an exam!">
          <v-radio
            v-for="(radio, key) in modes.exam"
            :key="key"
            :label="radio.label"
            :value="key"
            class="radio"
            color="success"
          />
        </v-radio-group>
      </div>

      <div class="params">
        <template v-if="isStudy">
          <v-select
            v-model="tag"
            label="Category"
            :items="tags"
            :rules="required_rule"
          />
          <v-select v-model="size" label="Num. Questions" :items="sizes"/>
          <v-select v-model="level" label="Level" :items="levels"/>
        </template>
        <template v-if="['failed'].includes(mode)">
          <v-select
            v-model="tag"
            label="Category"
            :items="tags"
            :rules="required_rule"
          />
          <v-select v-model="size" label="Num. Questions" :items="sizes"/>
        </template>
        <template v-if="['recent', 'irregular'].includes(mode)">
          <v-select v-model="size" label="Num. Questions" :items="sizes"/>
        </template>
      </div>
    </v-form>

    <v-footer absolute min-height="60px">
      <v-spacer/>
      <v-btn color="primary" @click="submit">Let's go!</v-btn>
    </v-footer>
  </v-card>
</template>

<script>
const _ = require('underscore')

function keyboard(event) {
  if (!['Enter', 'NumpadEnter'].includes(event.code)) {
    return
  }
  this.submit()
}

function nextMode() {
  const mode = this.$route.query.mode || this.$route.query.type

  switch (mode) {
    case 'study': {
      return 'hidden'
    }
    case 'hidden': {
      return 'today'
    }
    case 'today': {
      return 'daily'
    }
    default: {
      return 'study'
    }
  }
}

function tags() {
  return this.$store.state.tags.all
}

function isStudy() {
  return ['study', 'hidden'].includes(this.mode)
}

function url() {

  if (this.isStudy) {
    return this.studyUrl()
  }
  return this.examUrl()
}

function studyUrl() {
  return this.$router.resolve({
    name: 'cards-mode-category-level-size',
    params: {
      mode: this.mode,
      size: this.size,
      level: this.level,
      category: this.tag,
    },
  }).href
}

function examUrl() {
  let route = 'exam-type'

  if (['failed'].includes(this.mode)) {
    route = 'exam-type-size'
    if (this.tag !== 'all') {
      route = 'exam-type-size-category'
    }
  }

  return this.$router.resolve({
    name: route,
    params: {
      type: this.mode,
      size: this.size,
      category: this.tag,
    },
  }).href
}

function submit() {
  if (!this.$refs.form.validate()) {
    return
  }

  this.$router.push(this.url)
}

export default {
  data() {
    return {
      modes: {
        study: {
          study: {label: 'Learn new words', group: 'study'},
          hidden: {label: 'Self-test', group: 'study'},
        },
        exam: {
          today: {label: 'Test new words', group: 'exam'},
          daily: {label: 'Daily review', group: 'exam'},
          failed: {label: 'The hardest', group: 'exam'},
          irregular: {label: 'Irregular verbs', group: 'exam'},
        },
      },
      mode: this.nextMode(),
      size: (this.$route.query.size || 10) * 1,
      tag: (this.$route.query.category || 'b2') + '',
      sizes: [1, 10, 20, 50, 100],
      level: (this.$route.query.level || 1) * 1,
      levels: _.range(1, 11),
    }
  },
  computed: {
    isStudy,
    tags,
    url,
  },
  created() {
    window.addEventListener('keydown', this.keyboard)
  },
  destroyed() {
    window.removeEventListener('keydown', this.keyboard)
  },
  methods: {
    studyUrl,
    examUrl,
    submit,
    keyboard,
    nextMode,
  },
}
</script>

<style lang="scss" scoped>
.config {
  div.mode,
  div.params {
    flex: 1 1 auto;
    padding: 2em 4em;
  }

  .radio {
    margin: 1em;
  }
}
</style>
