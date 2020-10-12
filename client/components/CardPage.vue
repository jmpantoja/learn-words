<template>
  <div>
    <card-wrapper :question="question" :loading="is_loading" />
    <back-button />
  </div>
</template>
<script>
import CardWrapper from '@/components/Card/CardWrapper'

export default {
  name: 'CardPage',
  components: { CardWrapper },
}
</script>

<script>
import { mapGetters } from 'vuex'
const _ = require('underscore')
export default {
  data() {
    return {
      payload: {
        mode: this.$route.params.mode,
        category: this.$route.params.category,
        level: this.$route.params.level,
        size: this.$route.params.size,
        type: this.$route.params.type,
      },
    }
  },
  computed: {
    ...mapGetters({
      question: 'questions/current',
      is_loading: 'questions/isLoading',
      is_finished: 'questions/finished',
    }),
    is_exam() {
      return ['study', 'hidden'].includes(this.$route.params.mode)
    },
  },
  mounted() {
    if (this.$route.params.mode) {
      this.$store.dispatch('questions/load', this.payload)
    } else {
      this.$store.dispatch('questions/exam', this.payload)
    }
  },
  watch: {
    is_finished(value, previous) {
      if (value === false) {
        return
      }
      const query = _.pick(this.payload, function (value, key) {
        return value
      })

      this.$router.push({
        path: '/',
        query,
      })
    },
  },
}
</script>
