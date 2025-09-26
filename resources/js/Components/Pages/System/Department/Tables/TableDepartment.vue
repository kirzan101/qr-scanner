<template>
    <c-data-table-server
        :headers="headers"
        :hover="true"
        module="departments"
        ref="tableDataRef"
    >
    </c-data-table-server>
</template>

<script setup>
import { ref } from "vue";

// import EditDepartment from "../Actions/EditDepartment.vue";
// import DeleteDepartment from "../Actions/DeleteDepartment.vue";

// Define props
const props = defineProps({
    errors: Object,
    flash: Object,
    can: Array,
});

const headers = ref([
    {
        title: "Department Code",
        align: "start",
        sortable: false,
        key: "code",
    },
    {
        title: "Department Name",
        align: "start",
        sortable: false,
        key: "name",
    },
    {
        title: "Description",
        align: "start",
        sortable: false,
        key: "description",
    },
    {
        title: "Created At",
        align: "start",
        sortable: false,
        key: "created_at",
    },
    {
        title: "Actions",
        align: "start",
        sortable: false,
        key: "actions",
    },
]);

const tableDataRef = ref(null);
const toggleLoadData = (value = {}) => {
    if (tableDataRef.value) {
        tableDataRef.value.loadData(value);
    }
};

defineExpose({
    toggleLoadData,
});
</script>
