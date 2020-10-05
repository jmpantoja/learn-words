<template>
  <v-card-actions style="width: 100%">
    <v-chip v-if="resume" label class="mr-1" color="success">
      {{ resume.success }}
    </v-chip>

    <v-chip v-if="resume" label class="mr-1" color="error">
      {{ resume.error }}
    </v-chip>

    <v-spacer />
    <question-reply v-if="reply" />
    <question-favourite v-if="favourite" />
    <question-speaker ref="speaker" :manual="speaker" :word="word" />
  </v-card-actions>
</template>
<script>
export default {
  props: {
    word: {
      type: Object,
      required: true,
    },
    resume: {
      type: [Object],
      required: false,
    },
    favourite: {
      type: Boolean,
    },
    speaker: {
      type: Boolean,
    },
    reply: {
      type: Boolean,
    },
  },
  methods: {
    play() {
      this.$refs.speaker.playIfNoMute()
    },
  },
}
</script>
