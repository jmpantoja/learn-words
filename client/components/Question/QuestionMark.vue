<template>
  <div
    class="question-card-inner mark"
    style="
      height: 85%;
      display: flex;
      align-items: center;
      justify-content: center;
    "
  >
    <v-progress-circular
      :rotate="-90"
      :size="300"
      :width="30"
      :value="value"
      color="success"
    >
      <span class="display-3">{{ value }}%</span>
    </v-progress-circular>
  </div>
</template>
<script>
export default {
  name: 'QuestionMark',
  props: {
    success: {
      type: Number,
    },
    error: {
      type: Number,
    },
  },
  computed: {
    total() {
      return this.success + this.error
    },
    value() {
      const percent = (this.success / this.total) * 100
      return Math.round(percent)
    },
  },
}
</script>
