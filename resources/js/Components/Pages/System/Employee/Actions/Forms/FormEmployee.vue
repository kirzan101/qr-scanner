<template>
    <v-form>
        <v-container>
            <v-row>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <v-text-field
                        variant="outlined"
                        density="compact"
                        label="First Name"
                        hide-details="auto"
                        v-model="form.first_name"
                        :error-messages="formErrors.first_name"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <v-text-field
                        hide-details="auto"
                        variant="outlined"
                        density="compact"
                        label="Middle Name"
                        v-model="form.middle_name"
                        :error-messages="formErrors.middle_name"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <v-text-field
                        hide-details="auto"
                        variant="outlined"
                        density="compact"
                        label="Last Name"
                        v-model="form.last_name"
                        :error-messages="formErrors.last_name"
                    />
                </v-col>

                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <v-text-field
                        hide-details="auto"
                        variant="outlined"
                        density="compact"
                        label="Unique Identifier"
                        v-model="form.unique_identifier"
                        :error-messages="formErrors.unique_identifier"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <v-text-field
                        hide-details="auto"
                        variant="outlined"
                        density="compact"
                        label="Email"
                        type="email"
                        v-model="form.email"
                        :error-messages="formErrors.email"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <PositionSelect
                        label="Position"
                        v-model="form.position"
                        :positions="computedFilterPositions"
                        :error-messages="formErrors.position"
                        hide-details="auto"

                    />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <v-text-field
                        hide-details="auto"
                        variant="outlined"
                        density="compact"
                        label="Meal Entitlement"
                        v-model="form.meal_entitlement"
                        :error-messages="formErrors.meal_entitlement"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <DepartmentAutoComplete
                        label="Department"
                        v-model="form.department_id"
                        :departments="departments"
                        :error-messages="formErrors.department_id"
                    />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" xxl="6">
                    <PropertySelect
                        label="Property"
                        v-model="form.property_id"
                        :properties="properties"
                        :error-messages="formErrors.property_id"
                    />
                </v-col>
                <!-- Show if Position is OJT -->
                <v-col cols="12" md="6" v-if="isOJT">
                    <v-text-field
                        label="Start Date"
                        v-model="form.start_date"
                        type="date"
                        hide-details="auto"
                        density="compact"
                        class="my-1"
                    />
                </v-col>

                <v-col cols="6" md="6" v-if="isOJT">
                    <v-text-field
                        label="End Date"
                        v-model="form.end_date"
                        type="date"
                        hide-details="auto"
                        density="compact"
                    />
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import DepartmentAutoComplete from "./Components/DepartmentAutoComplete.vue";
import PositionSelect from "./Components/PositionSelect.vue";
import PropertySelect from "./Components/PropertySelect.vue";
// Define props

const props = defineProps({
    profile: Object,
    departments: Array,
    positions: Array,
    properties: Array,
    locations: Array,
    errors: Object,
    flash: Object,
    can: Array,
});
// Computed property to filter locations based on selected property
const filteredLocations = computed(() => {
    if (!form.value.property_id || !props.locations) {
        return props.locations || [];
    }
    return props.locations.filter(location => location.property_id === form.value.property_id);
});

const form = ref({
    id: null,
    first_name: null,
    middle_name: null,
    last_name: null,
    unique_identifier: null,
    username: null,
    email: null,
    position: null,
    property_id: null,
    location_id: null,
    is_able_to_login: null,
    department_id: null,
    start_date: null,
    end_date: null,
    meal_entitlement: null,
});

watch(
    () => props.profile,
    (newVal) => {
        if (newVal && Object.keys(newVal).length > 0) {
            Object.keys(form.value).forEach((key) => {
                const isNull = form.value[key] === null;
                const isEmptyArray =
                    Array.isArray(form.value[key]) &&
                    form.value[key].length === 0;

                // Only update if the current value is null or an empty array
                if (isNull || isEmptyArray) {
                    if (newVal.hasOwnProperty(key)) {
                        form.value[key] = newVal[key];
                    }
                }
            });
        }
    },
    { immediate: true, deep: true }
);

// Watch for property changes and reset location selection
watch(
    () => form.value.property_id,
    (newPropertyId, oldPropertyId) => {
        // Reset location selection when property changes (but not on initial load)
        if (oldPropertyId !== undefined && oldPropertyId !== null && newPropertyId !== oldPropertyId) {
            form.value.location_id = null;
        }
    }
);

const computedFilterPositions = computed(() => {
    return props.positions.filter((position) => position !== "Manager");
});

// Show/hide OJT fields Date Created and Date end
const isOJT = computed(() => form.value.position === "OJT");
watch(
    () => form.position,
    (newVal) => {
        if (newVal === "OJT") {
            const today = new Date().toISOString().slice(0, 10); // YYYY-MM-DD
            const end = new Date();
            end.setMonth(end.getMonth() + 6); // example: 6-month expiry

            form.date_created = today;
            form.date_end = end.toISOString().slice(0, 10);
        } else {
            form.date_created = null;
            form.date_end = null; // or keep values but hide them
        }
    }
);

// set error start
const formErrors = ref({});
watch(
    () => props.errors,
    (newValue) => {
        formErrors.value = Object.assign({}, newValue);
    },
    { deep: true }
);
// set error end

const emits = defineEmits(["formValues"]);

const emitFormData = () => {
    emits("formValues", form.value);
};

defineExpose({
    emitFormData,
});
</script>
