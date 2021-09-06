<template>
    <div @keydown.backspace="removeLastMarker">
        <field-map :bounds="bounds" :center="center" :field="field" @click="createMarker">
            <l-marker v-for="marker in markers" :lat-lng="marker" :draggable="edit" @dragend="triggerChange">
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
            console.log("this is our event:", evt)
            console.log("this is our markers:", this.markers)
            this.$emit('input', this.markers);
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
            this.markers.push(evt.latlng);
            this.triggerChange();
        },
    },

    components: {
        LMarker,
        LIcon,
        LPolygon,
    }
}
</script>
