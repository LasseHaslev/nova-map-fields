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
      const cords = markers
        .map((marker) => `${marker.lng},${marker.lat};`)
        .slice(0, -1);

      const URL = `https://api.mapbox.com/directions/v5/mapbox/driving/${cords}?geometries=geojson&access_token=pk.eyJ1Ijoia2V2aW5taWRib2UiLCJhIjoiY2pydWhlamQyMHJ2NTRhdGN1em5ndXVyMyJ9.Ejdo_3iuuGOD662Bh6es4w`;
      return fetch(URL)
        .then((resp) => resp.json())
        .then((response) => response.routes[0].geometry.coordinates)
        .then(this.mapNavigationRoute);
    },

    mapNavigationRoute(coords) {
      return coords.map(([lng, lat]) => {
        return { lat, lng };
      });
    },

    async triggerChange(markers) {
      if (markers.length === 0) {
        return;
      }

      console.log("forwarding markers:", markers);
      const navigationRouteCoords = await this.queryNavigation(markers);
      console.log("got navigationRouteCoords:", navigationRouteCoords);

      this.$emit("input", navigationRouteCoords);
    },
  },
};
</script>
