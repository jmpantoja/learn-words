import * as Vue from "vue"

const Counter = {
  delimiters: ['${', '}'],
  data() {
    return {
      counter: 0
    }
  },
  mounted() {
    setInterval(() => {
    //  this.counter++
    }, 1000)
  }
}
Vue.createApp(Counter).mount('#counter')
