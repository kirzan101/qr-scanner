<template>
    <MainLayout>
        <ScanHistoryContent :errors="errors" :flash="flash" :can="can" :mealTypes="mealTypes" :positions="positions"/>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import ScanHistoryContent from "@/Components/Pages/System/ScanHistory/ScanHistoryContent.vue";

defineProps({
    errors: Object,
    flash: Object,
    can: Array,
    mealTypes: Array,
    positions: Array,
});
</script>
