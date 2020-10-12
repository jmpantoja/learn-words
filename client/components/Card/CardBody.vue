<template>
  <div class="card">
    <card-side
      :word="question.wording"
      :description="question.description"
      :sample="question.translation"
    />
    <v-divider />
    <card-side :type="type" :word="question.word" :sample="question.sample" />
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import CardSide from '~/components/Card/CardSide'

function solve(question) {
  if (!['study'].includes(this.type)) {
    return
  }

  const payload = {
    questionId: question.id,
    response: null,
  }
  this.$store.dispatch('questions/solve', payload)
}

export default {
  name: 'CardBody',
  components: { CardSide },
  props: {
    question: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapGetters({
      type: 'mode/type',
    }),
  },
  watch: {
    question(current) {
      this.solve(current)
    },
  },
  mounted() {
    this.solve(this.question)
  },
  methods: {
    solve,
  },
}
</script>

<style scoped lang="scss">
.card {
  display: flex;
  flex-direction: column;
  flex: 1 1 auto;
  .card-side {
    flex: 1 1 auto;
    .word {
      font-size: 4rem;
      font-weight: 300;
      line-height: 4rem;
      font-family: 'Roboto', sans-serif;
      text-transform: lowercase;
    }
  }
}
</style>
