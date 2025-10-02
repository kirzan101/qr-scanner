<template>
    <v-container fluid class="pa-4">
        <!-- Web view layout with camera on left and full column on right -->
        <v-row class="d-none d-md-flex" no-gutters>
            <!-- Left side: Camera and some last scanned items -->
            <v-col cols="6" class="pr-2">
                <!-- <ReadQrScanner @scanned="handleScanned" /> -->

                <CardQrScanner />

                <v-card class="mt-4" elevation="2" rounded="lg">
                    <v-card-title class="text-h6 font-weight-bold pa-4">
                        Recent Scanned:
                    </v-card-title>
                    <v-card-text class="pa-2">
                        <!-- Always show the 2 most recent items -->
                        <CardLastScanned
                            v-for="(item, index) in recentItems"
                            :key="`recent-${index}`"
                            :item="item"
                            :index="index"
                            @handleQRClick="handleQRClick"
                        />
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- Right side: Complete history with pagination -->
            <v-col cols="6" class="pl-2">
                <v-card elevation="2" rounded="lg">
                    <v-card-title class="text-h6 font-weight-bold pa-4">
                        Scan History:
                    </v-card-title>
                    <v-card-text class="pa-2" style="min-height: 400px">
                        <CardLastScanned
                            v-for="(item, index) in paginatedItemsWeb"
                            :key="`history-${index}`"
                            :item="item"
                            :index="index"
                            @handleQRClick="handleQRClick"
                        />
                    </v-card-text>

                    <!-- Pagination for web right column -->
                    <v-card-actions
                        class="justify-center"
                        v-if="totalPagesWeb > 1"
                    >
                        <v-pagination
                            v-model="currentPageWeb"
                            :length="totalPagesWeb"
                            :total-visible="5"
                            color="primary"
                            size="small"
                        />
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <!-- Mobile view layout with camera on top and scrollable cards with pagination -->
        <v-container class="d-flex d-md-none" fluid>
            <v-col cols="12">
                <CardQrScanner />

                <!-- <ReadQrScanner @scanned="handleScanned" /> -->

                <v-card class="mt-4" elevation="2" rounded="lg">
                    <v-card-text class="pa-2">
                        <CardLastScanned
                            v-for="(item, index) in paginatedItems"
                            :key="index"
                            :item="item"
                            :index="index"
                            @handleQRClick="handleQRClick"
                        />
                    </v-card-text>

                    <!-- Pagination for mobile view -->
                    <v-card-actions
                        class="justify-center"
                        v-if="totalPages > 1"
                    >
                        <v-pagination
                            v-model="currentPage"
                            :length="totalPages"
                            :total-visible="3"
                            color="primary"
                            size="small"
                        />
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-container>
    </v-container>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import CardQrScanner from "./Components/CardQrScanner.vue";
import CardLastScanned from "./Components/CardLastScanned.vue";

import ReadQrScanner from "./Components/ReadQrCode.vue";

// const form = ref({
//     unique_identifier: null,
// });

// const handleScanned = (result) => {
//     form.value.unique_identifier = result;
// };

// watch(
//     form,
//     (value) => {
//         if (value.unique_identifier !== null) {
//             console.log("Scanned from CardQrScanner:", value.unique_identifier);
//             handleFormSubmission();
//             form.value.unique_identifier = null;
//         }
//     },
//     {
//         deep: true,
//     }
// );

// const handleFormSubmission = async () => {
//     router.post("/scans", form.value, {
//         onSuccess: ({ props }) => {
//             // sessionStorage.setItem("token", props.token);
//         },
//         onError: () => {
//             //
//         },
//         onBefore: () => {},
//         onFinish: () => {},
//     });
// };

// Mobile pagination
const currentPage = ref(1);
const itemsPerPage = 5;

// Web right column pagination
const currentPageWeb = ref(1);
const itemsPerPageWeb = 6;

const scannedItems = ref([
    {
        name: "Castillo, Sherry",
        code: "dsa-zxc-456",
        time: "2024-09-24 11:00:00 am",
    },
    {
        name: "Test, Allan",
        code: "dsa-zxc-456",
        time: "2024-07-16 9:29:22 am",
    },
    {
        name: "Johnson, Mike",
        code: "abc-def-789",
        time: "2024-09-23 2:15:30 pm",
    },
    {
        name: "Smith, Sarah",
        code: "xyz-123-456",
        time: "2024-09-22 10:45:15 am",
    },
    {
        name: "Brown, David",
        code: "qwe-rty-789",
        time: "2024-09-21 4:30:22 pm",
    },
    {
        name: "Wilson, Emma",
        code: "poi-lkj-123",
        time: "2024-09-20 8:20:10 am",
    },
    {
        name: "Davis, John",
        code: "mnb-vcx-456",
        time: "2024-09-19 1:10:45 pm",
    },
]);

// Recent items - always shows the 2 most recent scans
const recentItems = computed(() => {
    return scannedItems.value.slice(0, 2);
});

// Mobile pagination computed properties
const paginatedItems = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return scannedItems.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(scannedItems.value.length / itemsPerPage);
});

// Web right column pagination computed properties (complete history)
const paginatedItemsWeb = computed(() => {
    const start = (currentPageWeb.value - 1) * itemsPerPageWeb;
    const end = start + itemsPerPageWeb;
    return scannedItems.value.slice(start, end);
});

const totalPagesWeb = computed(() => {
    return Math.ceil(scannedItems.value.length / itemsPerPageWeb);
});

// Function to add new scanned item (this will automatically update recent items)
const addNewScan = (newItem) => {
    // Add to the beginning of the array (most recent first)
    scannedItems.value.unshift(newItem);
};

const handleQRClick = (item, index) => {
    console.log("QR code clicked!", { item, index });
};
</script>
