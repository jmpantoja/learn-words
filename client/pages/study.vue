<template>
  <layout-center>
    <question-list :tags="tags" :num="num" :level="level" training/>
  </layout-center>
</template>
<script>
export default {
  data() {
    return {
      tags: this.$route.query.tags.split(','),
      num: this.$route.query.number * 1,
      level: this.$route.query.level * 1,
    }
  },
}
</script>
