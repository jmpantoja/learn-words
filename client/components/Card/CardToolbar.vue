<template>
  <v-card-actions style="background-color: lightblue">
    <btn-show-answer />
    {{ question.id }}
    <v-spacer />
    <btn-speaker :question="question" />
  </v-card-actions>
</template>
<script>
import { mapGetters, mapMutations } from 'vuex'
import BtnShowAnswer from '~/components/Toolbar/BtnShowAnswer'
import BtnSpeaker from '~/components/Toolbar/BtnSpeaker'

export default {
  name: 'CardToolbar',
  components: { BtnSpeaker, BtnShowAnswer },
  props: {
    question: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapGetters({
      hide_back_side: 'mode/hide_back_side',
    }),
  },
  methods: {
    ...mapMutations({
      toggle_hide_back: 'mode/toggle_hide_back',
    }),
  },
}
</script>
