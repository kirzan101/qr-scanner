<template>
    <v-card>
        <p class="decode-result">
            Last result: <b>{{ result }}</b>
        </p>

        <qrcode-stream
            :paused="paused"
            @detect="onDetect"
            @error="onError"
            @camera-on="resetValidationState"
        >
            <div v-if="validationFailure" class="validation-failure">
                This is NOT a URL!
            </div>

            <div v-if="validationPending" class="validation-pending">
                Long validation in progress...
            </div>
        </qrcode-stream>
    </v-card>
    <SnackBarTop ref="snackBarRef" />
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { QrcodeStream } from "vue-qrcode-reader";
import axiosInstance from "@/Utilities/axios";
import SnackBarTop from "@/Components/Utilities/SnackBarTop.vue";

// --- State Variables (reactive data) ---

// ref() is used to create reactive variables in the Composition API,
// equivalent to the 'data' properties in the Options API.
const isValid = ref(undefined);
const paused = ref(false);
const result = ref(null);

// --- Utility Functions (equivalent to methods) ---

/**
 * Creates a promise that resolves after a specified duration.
 * @param {number} ms - The duration in milliseconds.
 */
const timeout = (ms) => {
    return new Promise((resolve) => {
        window.setTimeout(resolve, ms);
    });
};

/**
 * Resets the validation state and the result.
 * Called when the user wants to clear the current scan and start fresh.
 */
const resetValidationState = () => {
    isValid.value = undefined;
    result.value = null;
};

/**
 * Handler for errors that occur during the QR code stream setup or operation.
 * Logs the error to the console.
 * @param {Error} error - The error object.
 */
const onError = console.error;

const items = ref(null);

// notification
const snackBarRef = ref(null);
const toggleSnackBar = (message, color) => {
    if (!snackBarRef.value) {
        return;
    }

    snackBarRef.value.showNotification(message, color);
};

const loadData = async () => {
    try {
        const response = await axiosInstance.post(
            `/profiles/${result.value}`,
            {}
        );

        items.value = response.data; // Set items
    } catch (error) {
        console.error("Error fetching data:", error);
        // Optionally, you could set an error state here
    }
};

const onDetect = async ([firstDetectedCode]) => {
    // Access the value of a ref using .value in the Composition API
    result.value = firstDetectedCode.rawValue;
    paused.value = true; // Pause the stream while validation is pending

    // 1. Simulate a long-running external validation check (e.g., API call)
    await loadData();

    // Set the validation state based on the check
    isValid.value = true; // For this example, we always set it to true after the delay

    // 2. Add a delay so the user has time to read the validation message
    await timeout(2000);

    paused.value = false; // Resume the stream to allow for a new scan
};

// --- Computed Properties ---

// computed() is used to create reactive properties that derive their value
// from other reactive state, equivalent to 'computed' in the Options API.

/**
 * Returns true if the stream is paused and validation is neither successful nor failed.
 */
const validationPending = computed(() => {
    return isValid.value === undefined && paused.value;
});

/**
 * Returns true if the last detected code passed validation.
 */
const validationSuccess = computed(() => {
    return isValid.value === true;
});

/**
 * Returns true if the last detected code failed validation.
 */
const validationFailure = computed(() => {
    return isValid.value === false;
});

watch(
    items,
    (value) => {
        if (value.data) {
            return toggleSnackBar(value.message, "accent");
        }

        toggleSnackBar(value.message, "error");
    },
    {
        deep: true,
    }
);
</script>
