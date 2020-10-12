<template>
  <v-card
    class="mx-auto d-flex flex-column"
    width="850"
    height="620"
    elevation="10"
  >
    <v-overlay :value="loading" absolute opacity="0.01">
      <v-progress-circular indeterminate size="110" color="primary" />
    </v-overlay>

    <template v-if="is_empty">
      <v-card-text> no se ha encontrado nada</v-card-text>
    </template>

    <template v-if="is_ready">
      <card-toolbar :question="question" />
      <card-body :question="question" />
      <card-navigation />
    </template>
  </v-card>
</template>
<script>
import CardBody from '~/components/Card/CardBody'
import CardNavigation from '~/components/Card/CardNavigation'
import CardToolbar from '~/components/Card/CardToolbar'

export default {
  name: 'CardWrapper',
  components: { CardToolbar, CardBody, CardNavigation },
  props: {
    question: {
      type: Object,
    },
    loading: {
      type: Boolean,
      required: true,
    },
  },
  computed: {
    is_empty() {
      return !this.loading && this.question === null
    },
    is_ready() {
      return (
        !this.loading &&
        typeof this.question === 'object' &&
        this.question !== null
      )
    },
  },
}
</script>
