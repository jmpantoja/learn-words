<template>
  <v-btn
    color="primary"
    elevation="2"
    fab
    x-large
    right
    absolute
    @click="click"
  >
    <v-icon>fa-undo-alt</v-icon>
  </v-btn>
</template>
<script>
export default {
  name: 'BackButton',
  methods: {
    click() {
      this.$router.push('/')
    },
  },
}
</script>
