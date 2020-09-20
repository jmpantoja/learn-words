<template>
  <v-card class="mx-auto" width="600" elevation="4">
    <v-card-text>
      <p class="display-3 text--primary">
        {{ word.wording }} {{ word.title }}
      </p>
      <p>
        {{ word.description }}
      </p>
      <div class="text--primary">
        well meaning and kindly. "a benevolent smile"
      </div>
    </v-card-text>

    <v-card-actions class="flex justify-space-between">

      <v-btn right :disabled="!enable_prev" text color="green accent-20" v-on:click="move(-1)">
        PREV
      </v-btn>

      <v-btn ref="btn" right :disabled="!enable_next" text color="green accent-20" v-on:click="move(1)">
        NEXT
      </v-btn>
    </v-card-actions>
  </v-card>
</template>


<script>
const axios = require("axios");
export default {
  data() {
    return {
      items: [],
      index: 0,
      enable_next: true,
      enable_prev: false,
      word: {}
    }
  },
  mounted() {
    axios
      .get('https://api.nuxtjs.dev/mountains')
      .then(response => (this.init(response.data)))
  },

  methods: {
    init: function (data) {
      this.items = data;
      this.word = this.items[0];
      this.$emit('change', 0, 'xxxx')
    },
    move: function (step) {
      var max = this.items.length - 1;
      var index = this.index + step;

      this.enable_prev = index > 0;
      this.enable_next = index < max;

      if (index >= 0 && index <= max) {
        this.index = index;
        this.word = this.items[index];
        this.$emit('change', index, this.word)
      }
    }
  }

}
</script>
