<template>
  <v-footer>
    <v-chip color="white">{{ positionLabel }}</v-chip>
    <v-spacer></v-spacer>

    <v-card-actions>
      <v-btn icon :disabled="is_first" @click="first">
        <v-icon>fa-angle-double-left</v-icon>
      </v-btn>
      <v-btn icon :disabled="is_first" @click="previous">
        <v-icon>fa-angle-left</v-icon>
      </v-btn>
      <v-btn icon :disabled="is_last" @click="next">
        <v-icon>fa-angle-right</v-icon>
      </v-btn>
      <v-btn icon :disabled="is_last" @click="last">
        <v-icon>fa-angle-double-right</v-icon>
      </v-btn>
    </v-card-actions>
  </v-footer>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

function positionLabel() {
  return `${this.index} / ${this.length}`
}

export default {
  name: 'CardNavigation',
  computed: {
    ...mapGetters({
      index: 'questions/position',
      length: 'questions/length',
      is_first: 'questions/isFirst',
      is_last: 'questions/isLast',
    }),
    positionLabel,
  },
  methods: {
    ...mapActions({
      advance: 'mode/advance',
      first: 'questions/first',
      previous: 'questions/previous',
      next: 'questions/next',
      last: 'questions/last',
    }),
    keyboard(event) {
      if (event.target.tagName === 'INPUT') {
        return
      }

      switch (event.code) {
        case 'Home':
          this.first()
          break
        case 'End':
          this.last()
          break
        case 'ArrowRight':
          this.next()
          break
        case 'ArrowLeft':
          this.previous()
          break
        case 'Enter': {
          this.advance()
          break
        }
      }
    },
  },
  created() {
    window.addEventListener('keydown', this.keyboard)
  },
  destroyed() {
    window.removeEventListener('keydown', this.keyboard)
  },
}
</script>

<style scoped>
.v-chip:hover::before {
  opacity: 0;
}
</style>
