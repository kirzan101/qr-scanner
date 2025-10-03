<template>
    <v-container fluid class="pa-4 py-2 px-0">
        <v-row>
            <!-- Camera and Recent Scanned -->
            <v-col cols="12" md="6" class="pr-md-2">
                
                <CardQrScanner />

                <!-- Recent Scanned - Show on all screen sizes -->
                <v-card
                    class="mt-2"
                    elevation="2"
                    rounded="lg"
                >
                    <v-card-text class="pa-2">
                        <v-card-title class="text-h6 font-weight-bold pa-2">
                            Recent Scanned:
                        </v-card-title>
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

            <!-- Scan History -->
            <v-col cols="12" md="6" class="pl-md-2">
                <v-card class="mt-4 mt-md-0" elevation="2" rounded="lg">
                    <v-card-title class="text-h6 font-weight-bold pa-2 ml-2">
                        Scan History:
                    </v-card-title>
                    <v-card-text class="pa-2" style="min-height: 400px">
                        <CardLastScanned
                            v-for="(item, index) in paginatedItems"
                            :key="`history-${index}`"
                            :item="item"
                            :index="index"
                            @handleQRClick="handleQRClick"
                        />
                    </v-card-text>

                    <!-- Pagination -->
                    <v-card-actions
                        class="justify-center"
                        v-if="totalPages > 1"
                    >
                        <v-pagination
                            v-model="currentPage"
                            :length="totalPages"
                            :total-visible="$vuetify.display.smAndDown ? 3 : 5"
                            color="primary"
                            size="small"
                        />
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import CardQrScanner from "./Components/CardQrScanner.vue";
import CardLastScanned from "./Components/CardLastScanned.vue";

import ReadQrScanner from "./Components/ReadQrCode.vue";

const form = ref({
    unique_identifier: null,
});

const handleScanned = (result) => {
    form.value.unique_identifier = result;
};

watch(
    form,
    (value) => {
        if (value.unique_identifier !== null) {
            console.log("Scanned from CardQrScanner:", value.unique_identifier);
            handleFormSubmission();
            form.value.unique_identifier = null;
        }
    },
    {
        deep: true,
    }
);

const handleFormSubmission = async () => {
    router.post("/scans", form.value, {
        onSuccess: ({ props }) => {
            // sessionStorage.setItem("token", props.token);
        },
        onError: () => {
            //
        },
        onBefore: () => {},
        onFinish: () => {},
    });
};

// Single pagination system
const currentPage = ref(1);
const itemsPerPage = computed(() => {
    // More items per page on larger screens
    return window.innerWidth >= 960 ? 6 : 5;
});

const scannedItems = ref([
    {
        name: "Castillo, Sherry",
        mealType: "Lunch",
        time: "2024-09-24 11:00:00 am",
    },
    {
        name: "Test, Allan",
        mealType: "breakfast",
        time: "2024-07-16 9:29:22 am",
    },
    {
        name: "Johnson, Mike",
        mealType: "abc-def-789",
        time: "2024-09-23 2:15:30 pm",
    },
    {
        name: "Smith, Sarah",
        mealType: "xyz-123-456",
        time: "2024-09-22 10:45:15 am",
    },
    {
        name: "Brown, David",
        mealType: "qwe-rty-789",
        time: "2024-09-21 4:30:22 pm",
    },
    {
        name: "Wilson, Emma",
        mealType: "poi-lkj-123",
        time: "2024-09-20 8:20:10 am",
    },
    {
        name: "Davis, John",
        mealType: "mnb-vcx-456",
        time: "2024-09-19 1:10:45 pm",
    },
]);

// Recent items - always shows the 2 most recent scans
const recentItems = computed(() => {
    return scannedItems.value.slice(0, 5);
});

// Single pagination system
const paginatedItems = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return scannedItems.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(scannedItems.value.length / itemsPerPage.value);
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
