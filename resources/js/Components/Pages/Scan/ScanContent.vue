<template>
    <v-main >
        <v-container >
            <v-row justify="center">
                <v-col cols="6" md="12" lg="6" xl="6" xxl="6">
                    <v-card class="mb-6" rounded="xl" elevation="0" variant="outlined" border="primary">
                        <v-container class="camera-container position-relative">
                            <v-responsive aspect-ratio="1" class="camera-viewfinder">
                                 
                                <v-container class="flip-camera-overlay d-flex justify-center align-end" style="position: absolute; left: 0; right: 0; bottom: 16px;">
                                    <v-btn icon color="transparent" class="ma-0 pa-0" elevation="0">
                                        <SvgIcon type="mdi" :path="cameraFlipPath" size="32" />
                                    </v-btn>
                                </v-container>
                            </v-responsive>
                        </v-container>
                    </v-card>
                </v-col>
            </v-row>
  
          <v-container class="mb-4">
            <h3 class="text-h6 font-weight-bold mb-3">Last scanned:</h3>
            
             <!-- Card for last scanned  -->
            <v-card 
              v-for="(item, index) in scanned" :key="index" class="mb-3" rounded="lg" elevation="3" variant="outlined" border="primary">
              <v-card-text class="pa-2">
                <v-row align="center" no-gutters>
                  <v-col >
                    <v-card-title class="text-body-1-bold font-weight-medium mb-1">{{ item.name }}</v-card-title>
                    <v-card-text class="text-body-2 font-weight-medium mb-1">{{ item.code }}</v-card-text>
                    <v-card-text class="text-caption text-grey-darken-1">{{ item.time }}</v-card-text>
                  </v-col>
                  <v-col cols="auto">
                    <v-container class="qr-code-icon d-flex justify-center align-center pa-3" style="cursor: pointer;" @click="handleQRClick(item, index)">
                    <SvgIcon type="mdi" :path="path" size="90" class="text-primary" />
                    </v-container>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>

          </v-container>
        </v-container>
      </v-main>
  
</template>
<script setup>
import { ref } from "vue";
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiQrcode, mdiCameraFlip, mdiCamera } from '@mdi/js';

const scanned = ref([
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
]);

const path = mdiQrcode;
const cameraFlipPath = mdiCameraFlip;
const cameraPath = mdiCamera;

const handleQRClick = (item, index) => {
    console.log('QR code clicked!', { item, index });
   
};

const props = defineProps({
    errors: Object,
    flash: Object,
    can: Array,
});
</script>
