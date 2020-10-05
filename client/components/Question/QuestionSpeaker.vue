<template>
  <div>
    <v-btn icon @click="toggle">
      <v-icon v-show="!mute">mdi-volume-source</v-icon>
      <v-icon v-show="mute">mdi-volume-off</v-icon>
    </v-btn>
    <v-btn v-if="manual" :disabled="!sound_available" icon @click="play()">
      <v-icon>mdi-play</v-icon>
    </v-btn>
  </div>
</template>
<script>
export default {
  name: 'QuestionSpeaker',
  props: {
    word: null,
    manual: {
      type: Boolean,
    },
  },
  data() {
    return {
      mute: true,
      no_sound: false,
    }
  },
  computed: {
    sound_available() {
      const url = String(this.word.sound)
      return url.length > 0
    },
  },
  watch: {
    word() {
      if (!this.mute) {
        this.manual && this.play()
      }
    },
  },
  created() {
    window.addEventListener('keydown', this.keyboard)
  },
  destroyed() {
    window.removeEventListener('keydown', this.keyboard)
  },
  methods: {
    toggle() {
      this.mute = !this.mute
      if (!this.mute) {
        this.manual && this.play()
      }
    },
    keyboard(event) {
      switch (event.code) {
        case 'ArrowUp':
          this.manual && this.play()
          break
      }
    },
    playIfNoMute() {
      !this.mute && this.play();
    },
    play() {
      if (this.sound_available) {
        new Audio(this.word.sound).play()
      }
    },
  },
}
</script>
