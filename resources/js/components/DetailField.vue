<template>
    <div>
        <panel-item :field="field">
            <template slot="value">
                <component :is="'field-' + field.map.type" :field="field" v-model="field.value"></component>
            </template>
        </panel-item>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
