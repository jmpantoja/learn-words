<template>
  <v-btn v-if="available" icon @click="toggle">
    <v-icon v-if="type === 'hidden'">fa-eye-slash</v-icon>
    <v-icon v-else>fa-eye</v-icon>
  </v-btn>
</template>
<script>
import { mapGetters, mapMutations } from 'vuex'

export default {
  name: 'BtnShowAnswer',
  computed: {
    ...mapGetters({
      type: 'mode/type',
      mode: 'mode/mode',
    }),
    available() {
      return this.mode === 'hidden'
    },
  },
  methods: {
    ...mapMutations({
      toggle: 'mode/toggle_show_answer',
    }),
  },
}
</script>
