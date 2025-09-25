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
        <!-- Camera placeholder using only Vuetify components -->
        <v-container 
          class="d-flex justify-center align-center h-100 bg-grey-lighten-4"
          style="border: 2px dashed #ccc; border-radius: 8px;"
        >
          <v-icon size="64" color="grey-darken-2">
            mdi-camera
          </v-icon>
        </v-container>
        
        <!-- Camera flip button overlay -->
        <v-container
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
  </v-card>
</template>

<script setup>
const flipCamera = () => {
  console.log("Camera flipped!")
}
</script>
