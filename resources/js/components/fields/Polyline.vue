<template>
    <div @keydown.backspace="removeLastMarker">
        <field-map :bounds="bounds" :center="center" :field="field" @click="createMarker">
            <l-marker v-for="marker in markers" :lat-lng="marker" :draggable="edit" @dragend="triggerChange" />
            <l-polyline :lat-lngs="value" :visible="true" />
        </field-map>
    </div>
</template>

<script>
import {LMarker, LPolyline} from 'vue2-leaflet'

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
        triggerChange() {
            this.$emit('input', this.markers);
        },
        removeLastMarker() {
            this.markers.splice(-1,1);
            this.triggerChange();
        },
        createMarker(evt) {
            this.markers.push(evt.latlng);
            this.triggerChange();
        }
    },

    components: {
        LMarker,
        LPolyline,
    }
}
</script>
