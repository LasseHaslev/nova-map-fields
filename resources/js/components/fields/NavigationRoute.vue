<template>
  <div>
    <field-polyline
      :edit="edit"
      :field="field"
      :value="value"
      @input="triggerChange"
    />
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: Object,
      default: null,
    },
    field: {
      type: Object,
      required: true,
    },
    edit: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      startBounds: undefined,
      markerBeingDragged: undefined,
      lastMarkerAddedTime: 0,
    };
  },

  methods: {
    queryNavigation(markers) {
      const cords =
        "6.985908836799895,61.28541170467011;7.092996407603286,61.23027059410228;10.761970380502788,59.927108594277456";
      const URL = `https://api.mapbox.com/directions/v5/mapbox/driving/${cords}?geometries=geojson&access_token=pk.eyJ1Ijoia2V2aW5taWRib2UiLCJhIjoiY2pydWhlamQyMHJ2NTRhdGN1em5ndXVyMyJ9.Ejdo_3iuuGOD662Bh6es4w`;
      fetch(URL)
        .then((resp) => resp.json())
        .then((response) => {
          const { coordinates } = response.routes.geometry;
          console.log("got coordinates:", coordinates);
        });
    },

    triggerChange(markers) {
      console.log("forwarding markers:", markers);
      this.queryNavigation(markers);
      this.$emit("input", markers);
    },
  },
};
</script>
