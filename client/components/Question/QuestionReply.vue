<template>
  <v-btn icon :href="url">
    <v-icon>mdi-replay</v-icon>
  </v-btn>
</template>

<script>
export default {
  computed: {
    url() {
      return this.$route.fullPath
    },
  },
}
</script>
