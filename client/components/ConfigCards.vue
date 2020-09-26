<template>
  <v-card class="'mx-auto'" width="700" height="350" elevation="4">
    <v-form ref="form" lazy-validation>
      <v-card-text style="padding: 3em">
        <v-row>
          <v-col cols="12">
            <v-switch v-model="training" label="Training" inset />
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
              placeholder="groups"
              :items="tagItems"
              :rules="notEmptyRule"
              multiple
            />
          </v-col>
        </v-row>
      </v-card-text>
      <v-footer absolute>
        <v-spacer />
        <v-card-actions>
          <v-btn ref="btn" text color="green accent-20" @click="submit">
            LET'S GO!
          </v-btn>
        </v-card-actions>
      </v-footer>
    </v-form>
  </v-card>
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
      tags: ['adjetives'],
      tagItems: [],
      number: 25,
      numbers: [10, 25, 50, 100],
      training: false,
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
  computed: {
    url() {
      const route = this.training ? 'study' : 'exam'
      return this.$router.resolve({
        name: route,
        query: {
          number: this.number,
          tags: this.tags.join(','),
        },
      }).href
    },
  },
  methods: {
    init(data) {
      this.tagItems = data.map((tag) => tag.tag)
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.$router.push(this.url)
      }
    },
  },
}
</script>
