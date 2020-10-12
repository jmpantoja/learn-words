<template>
  <div>
    <vue-chart
      chart-type="AreaChart"
      :columns="columns"
      :rows="rows"
      :options="options"
    ></vue-chart>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  methods: {
    ...mapActions({
      load: 'stats/load',
    }),
  },
  computed: {
    ...mapGetters({
      rows: 'stats/data',
    }),
  },
  data() {
    return {
      columns: [
        {
          type: 'string',
          label: 'Day',
        },
        {
          type: 'number',
          label: '1 Day',
        },
        {
          type: 'number',
          label: '3 Days',
        },
        {
          type: 'number',
          label: '1 Week',
        },
        {
          type: 'number',
          label: '2 Weeks',
        },
        {
          type: 'number',
          label: '1 Month',
        },
      ],
      options: {
        isStacked: true,
        title: 'Progress',
        hAxis: {
          title: 'Year',
          minValue: '2004',
          maxValue: '2007',
        },
        vAxis: {
          title: '',
          minValue: 0,
          maxValue: 500,
        },
        width: 1500,
        height: 700,
      },
    }
  },
  mounted() {
    this.load()
  },
}
</script>
