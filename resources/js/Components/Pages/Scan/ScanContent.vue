<template>
    <v-container fluid class="pa-4 py-2 px-0">
        <v-row>
            <!-- Camera and Recent Scanned -->
            <v-col cols="12" md="6" class="pr-md-2 d-flex flex-column">
                
                <CardQrScanner />

                <!-- Recent Scanned - Show on all screen sizes -->
                <v-card
                    class="mt-2 flex-grow-1 d-flex flex-column"
                    elevation="2"
                    rounded="lg"
                >
                    <v-card-text class="pa-2 flex-grow-1 d-flex flex-column">
                        <v-card-title class="text-h6 font-weight-bold pa-2">
                            Recent Scanned:
                        </v-card-title>
                        <v-container class="flex-grow-1 pa-0">
                            <!-- Loading state -->
                            <v-progress-circular
                                v-if="loading"
                                indeterminate
                                color="primary"
                                class="mx-auto d-block mt-4"
                            />
                            
                            <!-- Empty state -->
                            <v-alert
                                v-else-if="recentItems.length === 0"
                                type="info"
                                variant="tonal"
                                class="mt-2"
                            >
                                No recent scans found
                            </v-alert>
                            
                            <!-- Scan items -->
                            <CardLastScanned
                                v-else
                                v-for="(item, index) in recentItems"
                                :key="`recent-${index}`"
                                :item="item"
                                :index="index"
                                @handleQRClick="handleQRClick"
                            />
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- Scan History -->
            <v-col cols="12" md="6" class="pl-md-2">
                <v-card class="mt-4 mt-md-0 d-flex flex-column" elevation="2" rounded="lg" style="height: 100%;">
                    <v-card-title class="text-h6 font-weight-bold pa-2 ml-2">
                        Scan History:
                    </v-card-title>
                    <v-card-text class="pa-2 flex-grow-1 d-flex flex-column" style="min-height: 400px">
                        <v-container class="flex-grow-1 pa-0">
                            <!-- Loading state -->
                            <v-progress-circular
                                v-if="loading"
                                indeterminate
                                color="primary"
                                class="mx-auto d-block mt-4"
                            />
                            
                            <!-- Empty state -->
                            <v-alert
                                v-else-if="paginatedItems.length === 0"
                                type="info"
                                variant="tonal"
                                class="mt-2"
                            >
                                No scan history found
                            </v-alert>
                            
                            <!-- Scan items -->
                            <CardLastScanned
                                v-else
                                v-for="(item, index) in paginatedItems"
                                :key="`history-${index}`"
                                :item="item"
                                :index="index"
                                @handleQRClick="handleQRClick"
                            />
                        </v-container>
                    </v-card-text>

                    <!-- Pagination -->
                    <v-card-actions
                        class="justify-center mt-auto"
                        v-if="hasMultiplePages"
                    >
                        <v-pagination
                            v-model="currentPage"
                            :length="totalPages"
                            :total-visible="5"
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
import { ref, computed, watch, onMounted } from "vue";
import axiosInstance from "@/Utilities/axios";
import CardQrScanner from "./Components/CardQrScanner.vue";
import CardLastScanned from "./Components/CardLastScanned.vue";

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
            // Reload scan history after successful scan
            fetchScanHistory();
        },
        onError: () => {
            //
        },
        onBefore: () => {},
        onFinish: () => {},
    });
};

// Laravel-style pagination
const currentPage = ref(1);
const perPage = computed(() => {
    // Maximize items per page based on screen size (Laravel paginate style)
    const width = window.innerWidth;
    if (width >= 1920) return 12;      // 4K/Large desktop
    if (width >= 1440) return 10;      // Desktop
    if (width >= 1200) return 8;       // Large tablet/small desktop
    if (width >= 768) return 7;        // Tablet
    return 6;                           // Mobile
});

// Scan history data from API
const scannedItems = ref([]);
const loading = ref(false);

// Fetch scan history from API
const fetchScanHistory = async () => {
    try {
        loading.value = true;
        const response = await axiosInstance.get('/scan-histories', {
            params: {
                per_page: 50, // Get recent 50 items
                sort_by: 'scanned_at',
                sort_direction: 'desc'
            }
        });
        
        // Transform API data to match CardLastScanned format
        if (response.data && response.data.data) {
            scannedItems.value = response.data.data.map(item => {
                const profile = item.profile || {};
                const firstName = profile.first_name || '';
                const middleName = profile.middle_name || '';
                const lastName = profile.last_name || '';
                
                return {
                    name: `${lastName}, ${firstName} ${middleName}`.trim(),
                    mealType: item.meal_schedule || 'N/A',
                    time: formatDateTime(item.scanned_at),
                    raw: item // Keep raw data for reference
                };
            });
        }
    } catch (error) {
        console.error('Error fetching scan history:', error);
    } finally {
        loading.value = false;
    }
};

// Format date time to Philippine Time
const formatDateTime = (dateTime) => {
    if (!dateTime) return '';
    const date = new Date(dateTime);
    return date.toLocaleString('en-PH', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
        timeZone: 'Asia/Manila'
    });
};

// Fetch data on component mount
onMounted(() => {
    fetchScanHistory();
});

// Laravel-style computed properties
const recentItems = computed(() => scannedItems.value.slice(0, 5));

const paginatedItems = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return scannedItems.value.slice(start, start + perPage.value);
});

const totalPages = computed(() => Math.ceil(scannedItems.value.length / perPage.value));
const hasMultiplePages = computed(() => totalPages.value > 1);

// Laravel-style methods
const addNewScan = (item) => scannedItems.value.unshift(item);
const handleQRClick = (item, index) => console.log('QR clicked:', { item, index });
</script>
