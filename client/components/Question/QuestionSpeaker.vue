<template>
  <div>
    <v-btn icon @click="toggle">
      <v-icon v-show="!mute">mdi-volume-source</v-icon>
      <v-icon v-show="mute">mdi-volume-off</v-icon>
    </v-btn>
    <v-btn :disabled="!sound_available" icon @click="play()">
      <v-icon>mdi-play</v-icon>
    </v-btn>
  </div>

</template>
<script>
export default {
  name: 'QuestionSpeaker',
  props: {
    word: null,
  },
  data() {
    return {
      mute: true,
      no_sound: false,
    }
  },
  watch: {
    word() {
      if (!this.mute) {
        this.play()
      }
    },
  },
  computed: {
    sound_available() {
      const url = String(this.word.sound)
      return url.length > 0
    },
  },
  methods: {
    toggle() {
      this.mute = !this.mute
      if (!this.mute) {
        this.play()
      }
    },
    play() {
      if (this.sound_available) {
        new Audio(this.word.sound).play()
      }
    },
  },
}
</script>
