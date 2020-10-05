<template>
  <v-form ref="form" lazy-validation>
    <v-card-text style="padding: 3em">
      <v-row>
        <v-col cols="3">
          <v-select
            v-model="mode"
            label="Mode"
            :rules="notEmptyRule"
            :items="modes"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="3">
          <v-select
            v-model="number"
            label="Nº questions"
            :rules="notEmptyRule"
            :items="numbers"
          />
        </v-col>
        <v-col cols="6">
          <v-select
            v-model="tags"
            label="Groups"
            :items="tagItems"
            :rules="notEmptyRule"
            multiple
          />
        </v-col>
        <v-col cols="3">
          <v-select
            v-model="level"
            label="Level"
            :rules="notEmptyRule"
            :items="levels"
          />
        </v-col>
      </v-row>
    </v-card-text>
  </v-form>
</template>

<script>
import all_tags from '@/apollo/queries/all_tags'

const utils = require('underscore')
export default {
  data() {
    return {
      notEmptyRule: [
        function (value) {
          if (utils.isNumber(value)) {
            return value > 0 || "This field can't be empty"
          }
          return !utils.isEmpty(value) || "This field can't be empty"
        },
      ],
      tags: ['b2'],
      tagItems: [],
      number: 10,
      numbers: [10, 20, 50, 100],
      level: 1,
      levels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
      modes: ['Study', 'Exam'],
      mode: 'Study',
    }
  },
  apollo: {
    questions: {
      prefetch: true,
      query: all_tags,
      update(data) {
        this.init(data.all_tags)
      },
    },
  },
  methods: {
    init(data) {
      this.tagItems = data.map((tag) => tag.tag)
    },
    disabled() {
      return !this.$refs.form.validate()
    },
    submit() {
      if (!this.$refs.form.validate()) {
        return
      }

      const route = String(this.mode).toLowerCase()
      const url = this.$router.resolve({
        name: route,
        query: {
          number: this.number,
          level: this.level,
          tags: this.tags.join(','),
        },
      }).href

      this.$router.push(url)
    },
  },
}
</script>
