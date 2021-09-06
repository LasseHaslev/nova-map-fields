<template>
    <div :style="{ height: field.map.height }">
        <l-map
            style="z-index: 0"
            :zoom="field.map.zoom"
            :center="center"
            :bounds="bounds"
            @click="onMapClick"
        >
            <l-tile-layer :url="url" :attribution="attribution" />
            <slot></slot>
        </l-map>
    </div>
</template>

<script>
import { LMap, LTileLayer } from "vue2-leaflet";

export default {
    props: {
        field: {
            type: Object,
            requred: true,
        },
        center: {
            type: Object,
            default: null,
        },
        bounds: {
            type: Object,
            default: null,
        },
    },

    data() {
        return {
            attribution:
                '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        };
    },

    methods: {
        onMapClick(evt) {
            this.$emit("click", evt);
        },
    },

    components: {
        LMap,
        LTileLayer,
    },
};
</script>

<style scoped>
@import "../../../node_modules/leaflet/dist/leaflet.css";
</style>
