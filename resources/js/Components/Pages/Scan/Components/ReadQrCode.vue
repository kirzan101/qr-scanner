<template>
    <!-- Make the container take full height for vertical centering -->
    <v-container>
        <div id="reader"></div>
        
        <!-- Success/Error Feedback Alert -->
        <v-alert
            v-if="showFeedback"
            :type="feedbackType"
            variant="tonal"
            density="compact"
            class="mt-2"
            closable
            @click:close="showFeedback = false"
        >
            {{ feedbackMessage }}
        </v-alert>
    </v-container>
</template>

<script setup>
import { ref, watch, nextTick, onMounted, onUnmounted } from "vue";
import { Html5Qrcode, Html5QrcodeScannerState } from "html5-qrcode";

const props = defineProps({
    errors: Object,
    flash: Object,
    btnDisabled: Boolean,
});

let html5QrCode;
let timeoutId;

const emits = defineEmits(["scanned", "timeout", "success", "error"]);

// UI Feedback state
const showFeedback = ref(false);
const feedbackMessage = ref('');
const feedbackType = ref('success');

// QR Code tracking for duplicate prevention
const scannedQRCodes = ref(new Map()); // Map to store QR code with timestamp

const isQRCodeRecentlyScanned = (qrCode) => {
    const now = Date.now();
    const oneHour = 60 * 60 * 1000; // 1 hour in milliseconds
    
    if (scannedQRCodes.value.has(qrCode)) {
        const lastScanTime = scannedQRCodes.value.get(qrCode);
        return (now - lastScanTime) < oneHour;
    }
    return false;
};

const addQRCodeToHistory = (qrCode) => {
    const now = Date.now();
    scannedQRCodes.value.set(qrCode, now);
    
    // Clean up old entries (older than 1 hour)
    const oneHour = 60 * 60 * 1000;
    for (const [code, timestamp] of scannedQRCodes.value.entries()) {
        if ((now - timestamp) >= oneHour) {
            scannedQRCodes.value.delete(code);
        }
    }
};

const showSuccessMessage = (message) => {
    feedbackMessage.value = message;
    feedbackType.value = 'success';
    showFeedback.value = true;
    console.log("Success:", message);
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        showFeedback.value = false;
    }, 3000);
};

const showErrorMessage = (message) => {
    feedbackMessage.value = message;
    feedbackType.value = 'error';
    showFeedback.value = true;
    console.error("Error:", message);
    
    // Auto-hide after 5 seconds for errors
    setTimeout(() => {
        showFeedback.value = false;
    }, 5000);
};

const startScanner = async () => {
    await nextTick(); // Wait until #reader is in the DOM
    
    try {
        html5QrCode = new Html5Qrcode("reader");

        await html5QrCode
            .start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    scanned.value = decodedText;
                    console.log("Scanned:", decodedText);
                }
            );
        
        // Emit success when scanner starts successfully
        emits("success", "Scanner started successfully");
        showSuccessMessage("Camera ready - Point at QR code");

        // Set initial timeout to stop the scanner after 20 seconds if no QR is detected
        timeoutId = setTimeout(() => {
            stopScanner();
            emits("timeout");
            showErrorMessage("Scanner timeout - No QR detected");
        }, 20000);
        
    } catch (err) {
        const errorMsg = `Scanner failed to start: ${err.message}`;
        console.error("Start failed:", err);
        emits("error", errorMsg);
        showErrorMessage(`Camera error: ${err.message}`);
    }
};

const stopScanner = async () => {
    try {
        if (
            html5QrCode &&
            html5QrCode.getState() === Html5QrcodeScannerState.SCANNING
        ) {
            await html5QrCode.stop();
            await html5QrCode.clear();
            console.log("Scanner stopped successfully");
            emits("success", "Scanner stopped successfully");
            showSuccessMessage("Camera stopped");
        }
    } catch (error) {
        const errorMsg = `Error stopping scanner: ${error.message}`;
        console.error("Error stopping scanner:", error.message);
        emits("error", errorMsg);
        showErrorMessage(`Stop error: ${error.message}`);
    }
};

const scanned = ref(null);

watch(
    scanned,
    (value) => {
        if (value !== null) {
            try {
                // Validate QR code format/content
                if (value.trim() === '') {
                    throw new Error('Empty QR code detected');
                }
                
                if (value.length < 3) {
                    throw new Error('QR code too short - invalid format');
                }
                
                // Check if QR code was recently scanned (within 1 hour)
                if (isQRCodeRecentlyScanned(value)) {
                    throw new Error(`QR code already scanned recently.`);
                }
                
                // Clear timeout since valid QR was detected
                clearTimeout(timeoutId);
                
                // Add QR code to history to prevent duplicate scanning
                addQRCodeToHistory(value);
                
                // Emit success and scanned data
                const successMsg = `QR code scanned successfully: ${value}`;
                emits("success", successMsg);
                emits("scanned", value);
                showSuccessMessage(`QR Scanned: ${value.substring(0, 20)}${value.length > 20 ? '...' : ''}`);
                
                // Reset scanned value and restart timeout for continuous scanning
                setTimeout(() => {
                    scanned.value = null;
                    // Restart timeout for next scan
                    timeoutId = setTimeout(() => {
                        stopScanner();
                        emits("timeout");
                        showErrorMessage("Scanner timeout - No QR detected");
                    }, 20000);
                }, 1000); // Brief pause before allowing next scan
                
            } catch (error) {
                const errorMsg = `QR validation failed: ${error.message}`;
                console.error("QR validation failed:", error.message);
                emits("error", errorMsg);
                showErrorMessage(`${error.message}`);
                
                // Reset scanned value to allow retry
                setTimeout(() => {
                    scanned.value = null;
                }, 1000);
            }
        }
    },
    {
        deep: true,
    }
);

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
