<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="12" lg="12" xl="12" xxl="12">
                <v-row justify="center">
                    <v-col
                        cols="10"
                        xs="10"
                        sm="6"
                        md="6"
                        lg="4"
                        xl="3"
                        xxl="3"
                    >
                        <v-card
                            rounded="xl"
                            elevation="0"
                            title="Scan QR"
                            prepend-icon="mdi-qrcode-scan"
                            variant="outlined"
                            border="primary"
                        >
                            <v-container
                                class="d-flex justify-center"
                                style="min-height: 200px"
                            >
                                <ReadQrCode />
                                <!-- <v-responsive
                                    aspect-ratio="1"
                                    class="camera-viewfinder"
                                >
                                    <v-container
                                        class="flip-camera-overlay d-flex justify-center align-end"
                                        style="
                                            position: absolute;
                                            left: 0;
                                            right: 0;
                                            bottom: 16px;
                                        "
                                    >
                                        <v-btn
                                            icon
                                            color="transparent"
                                            class="ma-0 pa-0"
                                            elevation="0"
                                        >
                                            <SvgIcon
                                                type="mdi"
                                                :path="cameraFlipPath"
                                                size="32"
                                            />
                                        </v-btn>
                                    </v-container>
                                </v-responsive> -->
                            </v-container>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row justify="center">
                    <v-col cols="12" md="12" lg="10" xl="8" xxl="6">
                        <v-container class="mb-4">
                            <h3 class="text-h6 font-weight-bold mb-3">
                                Last scanned:
                            </h3>

                            <!-- Card for last scanned  -->
                            <v-row justify="center">
                                <v-col
                                    v-for="(item, index) in scanned"
                                    :key="index"
                                    cols="12"
                                    md="6"
                                    lg="6"
                                    xl="6"
                                    xxl="6"
                                >
                                    <v-card
                                        class="mb-3"
                                        rounded="lg"
                                        elevation="3"
                                        variant="outlined"
                                        border="primary"
                                    >
                                        <v-card-text class="pa-2">
                                            <v-row align="center" no-gutters>
                                                <v-col>
                                                    <v-card-title
                                                        class="text-body-1-bold font-weight-medium mb-1"
                                                        >{{
                                                            item.name
                                                        }}</v-card-title
                                                    >
                                                    <v-card-text
                                                        class="text-body-2 font-weight-medium mb-1"
                                                        >{{
                                                            item.code
                                                        }}</v-card-text
                                                    >
                                                    <v-card-text
                                                        class="text-caption text-grey-darken-1"
                                                        >{{
                                                            item.time
                                                        }}</v-card-text
                                                    >
                                                </v-col>
                                                <v-col cols="auto">
                                                    <v-container
                                                        class="qr-code-icon d-flex justify-center align-center pa-3"
                                                        style="cursor: pointer"
                                                        @click="
                                                            handleQRClick(
                                                                item,
                                                                index
                                                            )
                                                        "
                                                    >
                                                        <SvgIcon
                                                            type="mdi"
                                                            :path="path"
                                                            size="90"
                                                            class="text-primary"
                                                        />
                                                    </v-container>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref } from "vue";
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiQrcode, mdiCameraFlip, mdiCamera } from "@mdi/js";
import ReadQrCode from "./Components/ReadQrCode.vue";

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
    console.log("QR code clicked!", { item, index });
};

const props = defineProps({
    errors: Object,
    flash: Object,
    can: Array,
});
</script>
