<template>
  <v-card width="700" height="380" elevation="4">
    <v-tabs v-model="tab" centered>
      <v-tabs-slider></v-tabs-slider>

      <v-tab href="#review"> Daily Review</v-tab>
      <v-tab href="#today"> Study new Words</v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item value="today">
        <config-form ref="today" />
      </v-tab-item>
      <v-tab-item value="review">
        <daily-review ref="review" @hasPendingReviews="hasPendingReviews" />
      </v-tab-item>
    </v-tabs-items>
    <v-footer absolute>
      <v-spacer />
      <v-card-actions>
        <v-btn ref="btn" rounded color="primary" :disabled="disabled" @click="submit">
          LET'S GO!
        </v-btn>
      </v-card-actions>
    </v-footer>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      tab: 'review',
      pendingReviews: 0,
    }
  },
  computed: {
    disabled() {
      return this.tab === 'review' && this.pendingReviews <= 0
    },
  },
  methods: {
    submit() {
      const tab = this.tab
      this.$refs[tab].submit()
    },
    hasPendingReviews(count) {
      this.pendingReviews = count
    },
  },
}
</script>
