<template>
    <div @keydown.backspace="removeLastMarker">
        <field-map :bounds="bounds" :center="center" :field="field" @click="createMarker">
            <l-marker v-for="marker in markers" :lat-lng="marker" :draggable="edit" @dragstart="saveDraggingMarker" @dragend="updateMarkerPosition">
                <l-icon
                    :icon-size="[24, 24]"
                    :icon-anchor="[12, 12]"
                    icon-url="/images/point_marker.svg"
                />
            </l-marker>
            <l-polygon :lat-lngs="value" :visible="true" />
        </field-map>

        <l-control position="bottomleft" v-if="edit" class="block my-2">
            <button @click="removeLastMarker" class="btn btn-default btn-primary">
            Slett forrige
            </button>
            <button @click="removeAllMarkers" class="btn btn-default btn-primary">
            Slett alle
            </button>
        </l-control>
    </div>
</template>

<script>
import {LMarker, LIcon, LPolygon} from 'vue2-leaflet'

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
        }
    },

    data() {
        return {
            startBounds: undefined,
            markerBeingDragged: undefined,
            lastMarkerAddedTime: 0
        }
    },

    computed: {
        markers() {
            if (!this.value[0]) {
                return [];
            }

            return this.value;
        },

        center() {
            if (!this.markers.length) {
                return {
                    lat: this.field.map.center.latitude,
                    lng: this.field.map.center.longitude,
                };
            }

            return this.bounds.getCenter();
        },

        bounds() {
            if (this.startBounds !== undefined) {
                return this.startBounds;
            }

            if (!this.markers.length) {
                return null;
            }

            return this.startBounds = new L.LatLngBounds(this.markers);
        },
    },

    methods: {
        triggerChange(evt) {
            this.$emit('input', this.markers);
        },
        saveDraggingMarker(evt) {
            this.markerBeingDragged = evt.target._latlng;
        },
        removeLastMarker(event) {
            this.markers.splice(-1,1);
            this.triggerChange();
            event.preventDefault();
        },
        removeAllMarkers(event) {
            this.value = [];
            this.triggerChange();
            event.preventDefault();
        },
        createMarker(evt) {
            if (new Date().getTime() - this.lastMarkerAddedTime < 20) {
                // Don't want to double register clicks from dragend, if within 20 ms return
                return
            }
            this.markers.push(evt.latlng);
            this.triggerChange();
        },
        updateMarkerPosition(evt) {
            const newMarker = evt.target._latlng;
            const { lat, lng } = this.markerBeingDragged;

            const markerIndex = this.value.findIndex(marker => marker.lat === lat && marker.lng === lng);
            this.value.splice(markerIndex, 1, newMarker);
            this.lastMarkerAddedTime = new Date().getTime()
        },
    },

    components: {
        LMarker,
        LIcon,
        LPolygon,
    }
}
</script>
