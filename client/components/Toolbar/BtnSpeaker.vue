<template>
  <v-btn icon :disabled="!enabled" @click="toggle">
    <v-icon v-if="mute">fa-volume-mute</v-icon>
    <v-icon v-else>fa-volume-down</v-icon>
  </v-btn>
</template>
<script>
import { mapGetters, mapMutations } from 'vuex'
export default {
  name: 'BtnSpeaker',
  props: {
    question: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapGetters({
      mute: 'mode/mute',
      type: 'mode/type',
    }),
    enabled() {
      return this.question.sound.length > 0
    },
  },
  methods: {
    ...mapMutations({
      toggle: 'mode/toggle_mute',
    }),
    play() {
      if (
        !['study', 'result'].includes(this.type) ||
        !this.enabled ||
        this.mute
      ) {
        return
      }
      new Audio(this.question.sound).play()
    },
  },
  watch: {
    type() {
      this.play()
    },
  },
  updated() {
    this.play()
  },
}
</script>
