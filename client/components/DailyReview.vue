<template>
  <v-card-text class="ma-10">
    <p v-if="count > 0" class="display-1 text-gray">
      You have <strong class="primary--text">{{ count }}</strong> words
      <br />
      that needs to be reviewed today
    </p>

    <p v-if="count <= 0" class="display-1 text-gray">
      Congratulations! <br />
      You don't have words that needs to be review!
    </p>
  </v-card-text>
</template>

<script>
import review_count from '@/apollo/queries/daily_review_count'

export default {
  data() {
    return {
      count: 0,
    }
  },
  apollo: {
    remaining: {
      prefetch: true,
      query: review_count,
      variables() {
        return {
          userId: '28408286-0245-11eb-98fa-0242ac130007',
        }
      },
      update(data) {
        this.count = data.daily_review_count
        this.$emit('hasPendingReviews', this.count)
      },
    },
  },
  methods: {
    submit() {
      const url = this.$router.resolve({
        name: 'review',
      }).href

      this.$router.push(url)
    },
  },
}
</script>
