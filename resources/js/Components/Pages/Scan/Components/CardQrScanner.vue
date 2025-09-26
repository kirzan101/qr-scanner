<template>
  <v-card
    elevation="2"
    rounded="lg"
    variant="outlined"
  >
    <v-container class="pa-4">
      <v-responsive
        aspect-ratio="1"
        class="position-relative"
      >
        <!-- Camera scanner when active -->
        <ReadQrCode 
          v-if="scannerActive"
          :errors="errors"
          :flash="flash"
          :btnDisabled="btnDisabled"
          @scanned="handleScanned"
          @timeout="handleTimeout"
          @success="handleSuccess"
          @error="handleError"
        />
        
        <!-- Restart button when scanner is inactive -->
        <v-container
          v-else
          class="d-flex flex-column justify-center align-center fill-height"
        >
          <v-icon
            size="64"
            color="grey-lighten-1"
            class="mb-4"
          >
            mdi-camera-off
          </v-icon>
          <v-btn
            color="primary"
            variant="elevated"
            size="large"
            prepend-icon="mdi-camera"
            @click="restartScanner"
          >
            Start Scanner
          </v-btn>
        </v-container>
        
        <!-- Camera flip button overlay (only when scanner is active) -->
        <v-container
          v-if="scannerActive"
          class="position-absolute d-flex justify-center align-end pa-0"
          style="bottom: 16px; left: 0; right: 0;"
        >
          <v-btn
            icon
            color="primary"
            elevation="2"
            size="large"
            @click="flipCamera"
          >
            <v-icon>mdi-camera-flip</v-icon>
          </v-btn>
        </v-container>
      </v-responsive>
    </v-container>
    
    <!-- Status feedback at the bottom of the card -->
    <v-card-actions v-if="statusMessage">
      <v-alert
        :type="statusType"
        variant="tonal"
        density="compact"
        class="flex-grow-1"
      >
        {{ statusMessage }}
      </v-alert>
    </v-card-actions>
  </v-card>
</template>

<script setup>
import { ref } from 'vue';
import ReadQrCode from './ReadQrCode.vue';
 
// Define props that will be passed from parent component
const props = defineProps({
  errors: Object,
  flash: Object,
  btnDisabled: {
    type: Boolean,
    default: false
  }
});

// Define emits to pass scanned data to parent
const emits = defineEmits(['scanned', 'success', 'error']);

// Scanner state management
const scannerActive = ref(true);
const statusMessage = ref('');
const statusType = ref('info');

const handleScanned = (scannedData) => {
  console.log("QR Code scanned:", scannedData);
  emits('scanned', scannedData);
  
  // Update status
  statusMessage.value = `Scanned: ${scannedData.substring(0, 30)}${scannedData.length > 30 ? '...' : ''}`;
  statusType.value = 'success';
  
  // Clear status after 3 seconds
  setTimeout(() => {
    statusMessage.value = '';
  }, 3000);
};

const handleTimeout = () => {
  console.log("Scanner timeout - no QR detected for 20 seconds");
  scannerActive.value = false;
  statusMessage.value = 'Scanner timeout - Click to restart';
  statusType.value = 'warning';
};

const handleSuccess = (message) => {
  console.log("Scanner success:", message);
  emits('success', message);
  
  // Show brief success status
  if (message.includes('Camera ready')) {
    statusMessage.value = 'Camera ready - Point at QR code';
    statusType.value = 'info';
    
    // Clear after 2 seconds
    setTimeout(() => {
      statusMessage.value = '';
    }, 2000);
  }
};

const handleError = (errorMessage) => {
  console.error("Scanner error:", errorMessage);
  emits('error', errorMessage);
  
  // Show error status
  statusMessage.value = errorMessage;
  statusType.value = 'error';
  
  // Clear after 5 seconds
  setTimeout(() => {
    statusMessage.value = '';
  }, 5000);
};

const restartScanner = () => {
  console.log("Restarting scanner");
  scannerActive.value = true;
  statusMessage.value = '';
};

const flipCamera = () => {
  console.log("Camera flipped!")
}
</script>
