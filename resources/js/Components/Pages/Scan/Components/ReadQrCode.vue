<template>
    <v-card
        width="auto"
        max-width="1000"
        prepend-icon="mdi-qrcode-scan"
        title="Scan QR"
    >
        <!-- Make the container take full height for vertical centering -->
        <v-container
            class="d-flex align-center justify-center"
            style="min-height: 40vh"
        >
            <div id="reader" style="width: 300px"></div>
        </v-container>
        
        <h1>{{ scanned }}</h1>
    </v-card>
</template>

<script setup>
import { ref, watch, nextTick, onMounted, onUnmounted } from "vue";
import { Html5Qrcode, Html5QrcodeScannerState } from "html5-qrcode";

const props = defineProps({
    errors: Object,
    flash: Object,
    btnDisabled: Boolean,
});

const scanned = ref(null);
let html5QrCode;
let timeoutId;

const startScanner = async () => {
    await nextTick(); // Wait until #reader is in the DOM
    html5QrCode = new Html5Qrcode("reader");

    html5QrCode
        .start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                scanned.value = decodedText;
                console.log("Scanned:", decodedText);
            }
        )
        .catch((err) => {
            console.error("Start failed:", err);
        });

    // Set a timeout to stop the scanner after 10 seconds (10000 milliseconds)
    timeoutId = setTimeout(() => {
        stopScanner();
        console.log("Scanner stopped automatically after 20 seconds.");
    }, 20000);
};

const stopScanner = async () => {
    if (
        html5QrCode &&
        html5QrCode.getState() === Html5QrcodeScannerState.SCANNING
    ) {
        await html5QrCode.stop();
        await html5QrCode.clear();
    }
};

watch;

onMounted(() => {
    startScanner();
});

onUnmounted(() => {
    // Clear the timeout to prevent memory leaks if the component is unmounted
    // before the timer fires.
    clearTimeout(timeoutId);
    stopScanner();
});
</script>
