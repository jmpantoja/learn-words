<template>
  <layout-center>
    <question-list :tags="tags" :num="num" />
  </layout-center>
</template>

<script>
export default {
  data() {
    return {
      tags: this.$route.query.tags.split(','),
      num: this.$route.query.number * 1,
    }
  },
}
</script>
