<template>
  <v-card
    class="mb-3"
    rounded="lg"
    elevation="1"
    variant="outlined"
  >
    <v-card-text class="pa-3">
      <v-container no-gutters>
        <v-row align="center" no-gutters>
          <v-col>
            <v-card-title class="text-body-1 font-weight-bold pa-0 mb-1">
              {{ item.name }}
            </v-card-title>
            <v-card-text class="text-body-2 pa-0 mb-1">
              {{ item.code }}
            </v-card-text>
            <v-card-text class="text-caption text-grey-darken-1 pa-0">
              {{ item.time }}
            </v-card-text>
          </v-col>
          <v-col cols="auto">
            <v-container
              class="d-flex justify-center align-center pa-2"
              style="cursor: pointer"
              @click="handleQRClick(item, index)"
            >
              <v-icon
                size="48"
                color="primary"
              >
                mdi-qrcode
              </v-icon>
            </v-container>
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script setup>
const props = defineProps({
  item: Object,
  index: Number
})

const emit = defineEmits(['handleQRClick'])

const handleQRClick = (item, index) => {
  emit('handleQRClick', item, index)
}
</script>
