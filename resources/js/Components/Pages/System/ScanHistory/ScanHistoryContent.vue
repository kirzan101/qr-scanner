<template>
    <v-container fluid>
        <v-breadcrumbs
            :items="['Home', 'Scan Histories']"
            density="compact"
        ></v-breadcrumbs>
        <v-card class="pa-4" elevation="4">
            <v-row class="mt-1" justify="space-between">
                    <v-card-title> Scan History Records </v-card-title>
                <v-col cols="12" sm="4" md="4" lg="4" xl="4" xxl="4">
                    <c-search-field v-model="filters.search" clearable />
                </v-col>
            </v-row>
            <v-row class="mt-1" justify="start">
            <v-col cols="6" md="6" lg="6" xl="6" xxl="6">
                   <v-select
                        :items="positions"
                        label="Filter by Position"
                        v-model="filters.position"
                        density="compact"
                        variant="outlined"
                        clearable
                    ></v-select>
                </v-col>
            <v-col cols="6" md="6" lg="6" xl="6" xxl="6">
                    <v-select
                        :items="mealTypes"
                        label="Meal Type"
                        v-model="filters.meal_type"
                        density="compact"
                        variant="outlined"
                        clearable
                    ></v-select>
                </v-col>
            </v-row>
            <TableScanHistory
                :errors="props.errors"
                :flash="props.flash"
                :can="props.can"
                ref="tableRef"
            />
        </v-card>
    </v-container>
</template>
<script setup>
const props = defineProps({
    errors: Object,
    flash: Object,
    can: Array,
    mealTypes: Array,
    positions: Array,
});

import { useDebouncedWatch } from "@/Composables/useDebouncedWatch";
import { ref, watch } from "vue";
import TableScanHistory from "./Tables/TableScanHistory.vue";

const tableRef = ref(null);
const filters = ref({
    search: "",
    meal_type: null,
    position: null,
});

const toggleLoadData = (value = {}) => {
    if (tableRef.value) {
        tableRef.value.toggleLoadData(value);
    }
};

useDebouncedWatch(
    filters,
    (value) => {
        toggleLoadData(value);
    },
    undefined,
    { deep: true }
);

// reload data when flash message changes
watch(
    () => props.flash,
    () => {
        toggleLoadData(filters.value);
    },
    { immediate: true }
);

</script>
