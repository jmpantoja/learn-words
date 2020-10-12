<template>
  <v-app>
    <v-container id="container">
      <nuxt />
    </v-container>

    <v-footer app>
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      title: 'Learn Words',
    }
  },
  mounted() {
    this.$store.dispatch('tags/load')
  },
}
</script>

<style>
#container {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.card-side input {
  max-height: 80px !important;
  height: 80px !important;
  font-size: 3.5em;
  font-weight: 300;
}
</style>
