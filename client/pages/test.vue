<template>
  <v-layout>
    <v-flex>
      <v-card height="90vh" flat class="d-flex align-center">
        <card @change="change"/>
      </v-card>
    </v-flex>
  </v-layout>
</template>


<script>
import Card from "~/components/Card";

export default {
  components: {
    Card
  },
  data() {
    return {}
  },
  methods: {
    change: function (index, word) {
      console.log(index, word.description)
    },
    reply: function () {
      //  el componente Card lanzará el evento reply cuando se conteste a una pregunta, y aqui se va acumulando los aciertos y los errores
    }
  }
}
</script>
