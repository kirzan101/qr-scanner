<template>
    <c-data-table-server
        :headers="headers"
        :hover="true"
        module="profiles"
        ref="tableDataRef"
    >
    </c-data-table-server>
</template>

<script setup>
import { ref } from "vue";

// import EditProfile from "../Actions/EditProfile.vue";
// import ResetPassword from "../Actions/ResetPassword.vue";
// import SetAccountStatus from "../Actions/SetAccountStatus.vue";

// Define props
const props = defineProps({
    user_groups: Array,
    account_types: Array,
    errors: Object,
    flash: Object,
    can: Array,
});

const headers = ref([
    {
        title: "First Name",
        align: "start",
        sortable: false,
        key: "first_name",
    },
    {
        title: "Middle Name",
        align: "start",
        sortable: false,
        key: "middle_name",
    },
    {
        title: "last Name",
        align: "start",
        sortable: false,
        key: "last_name",
    },
    {
        title: "Last Login",
        align: "start",
        sortable: false,
        key: "last_login_at",
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
