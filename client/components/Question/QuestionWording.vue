<template>
  <div class="face">
    <p class="display-3 text--primary">{{ word }}</p>
    <span class="description">{{ description }}</span>
    <p v-if="displaySample" class="sample">
      {{ sample }}
    </p>
  </div>
</template>
<script>
export default {
  name: 'QuestionWording',
  props: {
    word: {
      type: String,
      required: true,
    },
    description: {
      type: String,
    },
    sample: {
      type: String,
    },
    displaySample: {
      type: Boolean,
    },
  },
}
</script>

<style>
span.description {
  color: #c0341d;
  font-style: italic;
  font-size: 1.5em;
}
.sample {
  font-size: 1.3em;
}
</style>
