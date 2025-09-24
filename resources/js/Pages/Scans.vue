<template>
    <v-container>
        <ScanContent :errors="errors" :flash="flash" :can="can" />
    </v-container>
</template>
<script setup>
import ScanContent from "../Components/Pages/Scan/ScanContent.vue";

const props = defineProps({
    errors: String,
    flash: String,
    can: Array,
});
</script>
