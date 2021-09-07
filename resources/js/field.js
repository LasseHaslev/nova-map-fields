Nova.booting((Vue, router) => {
    Vue.component("index-nova-map-fields", require("./components/IndexField"));
    Vue.component(
        "detail-nova-map-fields",
        require("./components/DetailField")
    );
    Vue.component("form-nova-map-fields", require("./components/FormField"));

    Vue.component("field-map", require("./components/FieldMap"));
    Vue.component("field-marker", require("./components/fields/Marker"));
    Vue.component("field-polyline", require("./components/fields/Polyline"));
    Vue.component("field-polygon", require("./components/fields/Polygon"));
    Vue.component(
        "field-navigation-route",
        require("./components/fields/NavigationRoute")
    );

    // Config leaflet images to load images from CDN
    L.Icon.Default.imagePath =
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/";
});
