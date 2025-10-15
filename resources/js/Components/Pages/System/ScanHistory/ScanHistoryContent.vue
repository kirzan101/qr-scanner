<template>
    <v-container>
        <v-breadcrumbs
            :items="['Home', 'Scan Histories']"
            density="compact"
        ></v-breadcrumbs>
        <v-card class="pa-4" elevation="4">
            <v-row class="mt-1" justify="space-between">
                <v-col cols="12" sm="8" md="6" lg="6" xl="6" xxl="6">
                    <v-card-title>
                        Scan History Records
                    </v-card-title>
                </v-col>
                <v-col cols="12" sm="4">
                    <c-search-field v-model="filters.search" clearable />
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
});

import { ref, watch } from "vue";
import TableScanHistory from "./Tables/TableScanHistory.vue";

const tableRef = ref(null);
const filters = ref({
    search: ''
});

// Watch for search changes and trigger table reload
watch(() => filters.value.search, (newValue) => {
    if (tableRef.value) {
        tableRef.value.toggleLoadData({
            search: newValue
        });
    }
});
</script>
