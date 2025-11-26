<template>
  <v-expand-transition>
    <v-card v-if="form.name" class="mt-9" outlined>
      <v-card-title class="text-h5 font-weight-bold mb-4">
        <v-icon class="mr-2">mdi-clock-outline</v-icon>
        Meal Schedule
      </v-card-title>

      <v-card-text>
        <!-- Table Layout -->
        <v-container fluid>
          <v-row class="mb-2">
            <v-col cols="3" class="font-weight-bold text-h6 border-b-lg">Day</v-col>
            <v-col cols="3" class="font-weight-bold text-h6 text-center border-b-lg">Breakfast</v-col>
            <v-col cols="3" class="font-weight-bold text-h6 text-center border-b-lg">Lunch</v-col>
            <v-col cols="3" class="font-weight-bold text-h6 text-center border-b-lg">Dinner</v-col>
          </v-row>

          <v-row 
            v-for="day in days" 
            :key="day"
            class="border-b py-2"
          >
            <v-col cols="3" class="font-weight-medium d-flex align-center">
              {{ day }} 
            </v-col>
            <v-col cols="3" class="text-center d-flex align-center justify-center">
              <v-btn
                variant="tonal"
                color="primary"
                size="small"
                @click="openDialog(day, 'breakfast')"
              >
                {{ formatTime(schedule[day].breakfast_start) }} - {{ formatTime(schedule[day].breakfast_end) }}
              </v-btn>
            </v-col>
            <v-col cols="3" class="text-center d-flex align-center justify-center">
              <v-btn
                variant="tonal"
                color="primary"
                size="small"
                @click="openDialog(day, 'lunch')"
              >
                {{ formatTime(schedule[day].lunch_start) }} - {{ formatTime(schedule[day].lunch_end) }}
              </v-btn>
            </v-col>
            <v-col cols="3" class="text-center d-flex align-center justify-center">
              <v-btn
                variant="tonal"
                color="primary"
                size="small"
                @click="openDialog(day, 'dinner')"
              >
                {{ formatTime(schedule[day].dinner_start) }} - {{ formatTime(schedule[day].dinner_end) }}
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-expand-transition>


  <v-dialog v-model="dialog" max-width="500" persistent>
    <v-card
      prepend-icon="mdi-clock-edit-outline"
      :title="selectedDay"
    >
      <v-container>     
        <v-row dense>
          <v-col cols="12" class="mb-2">
            <v-label class="text-caption mb-1">Start</v-label>
            <v-text-field
              v-model="editTimes.start"
              type="time"
              variant="outlined"
              density="compact"
              hide-details="auto"
            />
          </v-col>
          <v-col cols="12">
            <v-label class="text-caption mb-1">End</v-label>
            <v-text-field
              v-model="editTimes.end"
              type="time"
              variant="outlined"
              density="compact"
              hide-details="auto"
            />
          </v-col>
        </v-row>
      </v-container>

      <template v-slot:actions>
        <v-btn :disabled="btnDisabled" @click="cancelEdit">
          close
        </v-btn>
        <v-btn
          :disabled="btnDisabled"
          :loading="btnDisabled"
          variant="elevated"
          prepend-icon="mdi-check-circle"
          @click="saveSchedule"
        >
          save
        </v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { reactive, ref } from 'vue'

const props = defineProps({
  form: {
    type: Object,
    required: true,
  },
})

const emits = defineEmits(['update:schedule'])

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

const schedule = reactive({
  Monday: { breakfast_start: '07:00', breakfast_end: '10:00', lunch_start: '11:00', lunch_end: '14:00', dinner_start: '17:00', dinner_end: '20:00' },
  Tuesday: { breakfast_start: '07:00', breakfast_end: '10:00', lunch_start: '11:00', lunch_end: '14:00', dinner_start: '17:00', dinner_end: '20:00' },
  Wednesday: { breakfast_start: '07:00', breakfast_end: '10:00', lunch_start: '11:00', lunch_end: '14:00', dinner_start: '17:00', dinner_end: '20:00' },
  Thursday: { breakfast_start: '07:00', breakfast_end: '10:00', lunch_start: '11:00', lunch_end: '14:00', dinner_start: '17:00', dinner_end: '20:00' },
  Friday: { breakfast_start: '07:00', breakfast_end: '10:00', lunch_start: '11:00', lunch_end: '14:00', dinner_start: '17:00', dinner_end: '20:00' },
  Saturday: { breakfast_start: '07:00', breakfast_end: '10:00', lunch_start: '11:00', lunch_end: '14:00', dinner_start: '17:00', dinner_end: '20:00' },
  Sunday: { breakfast_start: '07:00', breakfast_end: '10:00', lunch_start: '11:00', lunch_end: '14:00', dinner_start: '17:00', dinner_end: '20:00' },
})

// Dialog state
const dialog = ref(false)
const selectedDay = ref('')
const selectedDayKey = ref('') // Store the actual day key for updating schedule
const selectedMealType = ref('')
const editTimes = reactive({
  start: '',
  end: ''
})
const btnDisabled = ref(false)

// Format time to 12-hour format with AM/PM
const formatTime = (time) => {
  if (!time) return ''
  const [hours, minutes] = time.split(':')
  const hour = parseInt(hours)
  const ampm = hour >= 12 ? 'PM' : 'AM'
  const displayHour = hour % 12 || 12
  return `${displayHour}:${minutes}${ampm}`
}

// Open dialog for editing a specific meal
const openDialog = (day, mealType) => {
  selectedDay.value = `${day} - ${mealType.charAt(0).toUpperCase() + mealType.slice(1)}`
  selectedDayKey.value = day // Store the actual day key
  selectedMealType.value = mealType
  
  // Copy current times for the selected meal
  editTimes.start = schedule[day][`${mealType}_start`]
  editTimes.end = schedule[day][`${mealType}_end`]
  
  dialog.value = true
}

// Save the edited meal times
const saveSchedule = () => {
  // Update schedule with edited times for the specific meal
  schedule[selectedDayKey.value][`${selectedMealType.value}_start`] = editTimes.start
  schedule[selectedDayKey.value][`${selectedMealType.value}_end`] = editTimes.end
  
  dialog.value = false
  
  // Emit updated schedule
  emits('update:schedule', schedule)
}

// Cancel editing
const cancelEdit = () => {
  dialog.value = false
}
 
</script>
