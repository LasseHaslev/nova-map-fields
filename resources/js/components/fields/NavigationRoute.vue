<template>
  <div>
    <field-polyline
      :edit="edit"
      :field="field"
      :value="localValue"
      @input="triggerChange"
    />

    <p v-if="edit"><b>Velg rutetype:</b></p>
    <div v-if="edit" class="flex block my-2">
      <div v-for="option in routeTypes" class="ml-2">
        <input
	  type="radio"
	  :id="option.value"
	  :checked="option.value === selectedRouteType"
	  :value="option.value"
	  @change="updateRouteType">
        <label :for="option.value">{{ option.name }}</label>
      </div>
    </div>

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
      localValue: {},
      routeTypes: [{
        value: 'driving',
        name: 'Bil'
      },
      {
        value: 'walking',
        name: 'Gange'
      }],
      selectedRouteType: null
    };
  },

  created() {
    this.selectedRouteType = this.routeTypes[0].value;

    if (this.value !== null) {
      this.localValue = this.value;
    }
  },

  methods: {
    queryNavigation(markers) {
      const cords = markers
        .map((marker) => `${marker.lng},${marker.lat}`)
        .join(";");

      const API_URL = 'https://api.mapbox.com/directions/v5'
      let ACCESS_TOKEN = 'pk.eyJ1Ijoia2V2aW5taWRib2UiLCJhIjoiY2pydWhlamQyMHJ2NTRhdGN1em5ndXVyMyJ9.Ejdo_3iuuGOD662Bh6es4w';

      if (window.fjordmap && window.fjordmap.mapbox_access_token) {
        ACCESS_TOKEN = window.fjordmap.mapbox_access_token;
      }
      const URL = `${API_URL}/mapbox/${this.selectedRouteType}/${cords}?geometries=geojson&access_token=${ACCESS_TOKEN}`;
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

    updateRouteType(event) {
      this.selectedRouteType = event.target.value;
      this.triggerChange(this.localValue);
    },

    async triggerChange(markers) {
      if (markers.length === 0) {
        return;
      }

      this.localValue = markers;

      console.log("forwarding markers:", markers);
      const navigationRouteCoords = await this.queryNavigation(markers);
      console.log("got navigationRouteCoords:", navigationRouteCoords);

      this.$emit("input", navigationRouteCoords);
    },
  },
};
</script>
