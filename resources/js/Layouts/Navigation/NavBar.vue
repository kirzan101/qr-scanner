<template>
    <v-app-bar color="primary" class="px-2">
        <v-app-bar-nav-icon v-if="hasDrawer" @click="toggleDrawer" />
        <h2>QR Scanner</h2>
    </v-app-bar>
</template>

<script setup>
const props = defineProps({
    hasDrawer: {
        type: Boolean,
        default: true,
    },
    hasSettings: {
        type: Boolean,
        default: true,
    },
    hasChangPassword: Boolean,
    errors: Object,
    flash: Object,
    can: Array,
});

const emits = defineEmits(["toggleDrawer"]);
const toggleDrawer = () => {
    emits("toggleDrawer");
};
</script>
