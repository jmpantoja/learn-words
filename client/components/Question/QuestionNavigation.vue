<template>
  <v-card-actions style="width: 100%">
    <v-chip class="mr-3" color="white">
      {{ index + 1 }} / {{ total + 1 }}</v-chip
    >

    <v-spacer />
    <v-btn right :disabled="!prev_enabled" text color="primary" @click="prev">
      {{ label_prev }}
    </v-btn>
    <v-btn
      ref="btn"
      right
      :disabled="!next_enabled"
      text
      color="primary"
      @click="next"
    >
      {{ label_next }}
    </v-btn>
  </v-card-actions>
</template>
<script>
export default {
  name: 'QuestionNavigation',
  props: {
    index: {
      type: Number,
      required: true,
    },
    total: {
      type: Number,
      required: true,
    },
    label_next: {
      type: String,
      default: 'NEXT',
    },
    label_prev: {
      type: String,
      default: 'PREV',
    },
  },
  data() {
    return {
      position: this.index,
    }
  },
  computed: {
    next_enabled() {
      return this.index < this.total
    },
    prev_enabled() {
      return this.index > 0
    },
  },
  created() {
    window.addEventListener('keydown', this.keyboard)
  },
  destroyed() {
    window.removeEventListener('keydown', this.keyboard)
  },
  methods: {
    keyboard(event) {
      switch (event.code) {
        case 'ArrowRight':
          this.next()
          break
        case 'ArrowLeft':
          this.prev()
          break
      }
    },
    next() {
      if (this.index < this.total) {
        this.$emit('goTo', this.index + 1)
      }
    },
    prev() {
      if (this.index > 0) {
        this.$emit('goTo', this.index - 1)
      }
    },
  },
}
</script>

<style scoped>
.v-chip:hover::before {
  opacity: 0;
}
</style>
