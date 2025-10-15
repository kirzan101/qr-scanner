<template>
    <c-data-table-server
        :headers="headers"
        :hover="true"
        module="scan-histories"
        ref="tableDataRef"
    >
        <template #item.unique_identifier="{ item }">
            {{ item.profile?.unique_identifier || 'N/A' }}
        </template>

        <template #item.date="{ item }">
            {{ formatDate(item.scanned_at) }}
        </template>

        <template #item.time="{ item }">
            {{ formatTime(item.scanned_at) }}
        </template>

        <template #item.employee_name="{ item }">
            {{ getEmployeeName(item) }}
        </template>

        <template #item.department="{ item }">
            {{ item.profile?.department?.name || 'N/A' }}
        </template>

        <template #item.property="{ item }">
            {{ item.property?.name || 'N/A' }}
        </template>
        
        <template #item.meal_type="{ item }">
            <v-chip 
                :color="getMealTypeColor(item.meal_schedule)" 
                variant="elevated"
                size="small"
            >
                {{ item.meal_schedule || 'N/A' }}
            </v-chip>
        </template>
    </c-data-table-server>
</template>

<script setup>
import { ref } from "vue";

// Define props
const props = defineProps({
    errors: Object,
    flash: Object,
    can: Array,
});

const headers = ref([
    {
        title: "Unique Identifier",
        align: "start",
        sortable: true,
        key: "unique_identifier",
    },
    {
        title: "Employee Name",
        align: "start",
        sortable: true,
        key: "employee_name",
    },
    {
        title: "Department",
        align: "start",
        sortable: false,
        key: "department",
    },
    {
        title: "Property",
        align: "start",
        sortable: false,
        key: "property",
    },
    {
        title: "Date",
        align: "start",
        sortable: true,
        key: "date",
    },
    {
        title: "Time",
        align: "start",
        sortable: true,
        key: "time",
    },
    {
        title: "Meal Type",
        align: "start",
        sortable: true,
        key: "meal_type",
    },
]);

const tableDataRef = ref(null);

const toggleLoadData = (value = {}) => {
    if (tableDataRef.value) {
        tableDataRef.value.loadData(value);
    }
};

const getEmployeeName = (item) => {
    if (!item.profile) return 'N/A';
    const firstName = item.profile.first_name || '';
    const middleName = item.profile.middle_name || '';
    const lastName = item.profile.last_name || '';
    
    // Format: Last Name, First Name Middle Name
    return `${lastName}, ${firstName} ${middleName}`.trim();
};

const formatDate = (dateTime) => {
    if (!dateTime) return '';
    // Convert to Philippine Time (GMT+8)
    const date = new Date(dateTime);
    return date.toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        timeZone: 'Asia/Manila'
    });
};

const formatTime = (dateTime) => {
    if (!dateTime) return '';
    // Convert to Philippine Time (GMT+8)
    const date = new Date(dateTime);
    return date.toLocaleTimeString('en-PH', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
        timeZone: 'Asia/Manila'
    });
};

const getMealTypeColor = (mealType) => {
    const colors = {
        'Breakfast': 'orange',
        'Lunch': 'green',
        'Dinner': 'blue',
        'Snack': 'purple'
    };
    return colors[mealType] || 'grey';
};

defineExpose({
    toggleLoadData,
});
</script>
