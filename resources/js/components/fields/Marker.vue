<template>
    <field-map :center="position" :field="field">
        <l-marker :lat-lng="position" :draggable="edit" @dragend="onDragEnd"/>
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
        }
    },

    mounted() {
        this.registerWatchers();
    },

    methods: {
        onDragEnd(evt) {
            let latLng = evt.target.getLatLng();

            this.$emit('input', {
                lat: latLng.lat,
                lng: latLng.lng,
            });
        },

        registerWatchers() {
            this.getGlobalFieldComponents().forEach(component => {
                if ([this.field.latitudeField, this.field.longitudeField].includes(component.field.attribute)) {
                    component.$watch('value', (value) => {
                        this.value[component.field.attribute] = value;
                    }, {immediate: true})
                }
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
