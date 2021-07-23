<template>
    <field-map :center="markers[0]" :field="field">
        <l-marker v-for="pos in markers" :lat-lng="pos" :draggable="edit" @dragend="onDragEnd" @click="(event) => toggleMarker(event, Math.random())" />
            <!-- :opacity="Math.random() > 0.5 ? 1 : 0.5"/> -->
    </field-map>
</template>

<script>
import {LMarker} from 'vue2-leaflet'

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

    computed: {
        position() {
            return L.latLng(this.value.lat || this.field.map.center.latitude, this.value.lng || this.field.map.center.longitude);
        },
        markers() {
            let { selected_products, all_products } = this.field;
            return selected_products.map(prod => L.latLng(prod.lat, prod.lng))
        }
    },

    methods: {
        toggleMarker(event, id) {
            console.log('marker clicked!');
            console.log("id:", id)
        },

        onDragEnd(evt) {
            let latLng = evt.target.getLatLng();

            this.$emit('input', {
                lat: latLng.lat,
                lng: latLng.lng,
            });
        },

        registerWatchers() {
            this.getGlobalFieldComponents().forEach(component => {
                component.$watch('value', (value) => {
                    this.value['selected_products'] = value;
                }, {immediate: true})
            });

        },

        getGlobalFieldComponents(components = null) {
            if (! components) {
                components = this.$root.$children;
            }

            let returnArray = [];

            components.forEach(component => {
                if (component.field) {
                    return returnArray.push(component);
                }

                returnArray = returnArray.concat(this.getGlobalFieldComponents(component.$children));
            });

            return returnArray;
        },
    },

    components: {
        LMarker,
    }
}
</script>
