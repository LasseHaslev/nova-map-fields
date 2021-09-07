<template>
  <div>
    <field-polyline
      :edit="edit"
      :field="field"
      :value="localValue"
      @input="triggerChange"
    />

    <l-control position="topleft" v-if="edit" class="block my-2">
      <div class="flex">
	<div v-for="option in routeTypes" class="ml-2">
          <input type="radio" :id="option.value" :value="option.value" v-model="selectedRouteType">
          <label :for="option.value">{{ option.name }}</label>
	</div>
      </div>
    </l-control>

  </div>
</template>

<script>
import { LControl } from 'vue2-leaflet';

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
      }, {
	value: 'walking',
	name: 'Gange'
      }],
      selectedRouteType: null
    };
  },

  created() {
    this.selectedRouteTypes = this.routeTypes[0].value;

    if (this.value !== null) {
      this.localValue = this.value;
    }
  },

  methods: {
    queryNavigation(markers) {
      const cords = markers
        .map((marker) => `${marker.lng},${marker.lat}`)
        .join(";");

      const URL = `https://api.mapbox.com/directions/v5/mapbox/${this.selectedRouteType.value}/${cords}?geometries=geojson&access_token=pk.eyJ1Ijoia2V2aW5taWRib2UiLCJhIjoiY2pydWhlamQyMHJ2NTRhdGN1em5ndXVyMyJ9.Ejdo_3iuuGOD662Bh6es4w`;
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

      this.localValue = markers;

      console.log("forwarding markers:", markers);
      const navigationRouteCoords = await this.queryNavigation(markers);
      console.log("got navigationRouteCoords:", navigationRouteCoords);

      this.$emit("input", navigationRouteCoords);
    },
  },

  components: {
    LControl
  }
};
</script>
