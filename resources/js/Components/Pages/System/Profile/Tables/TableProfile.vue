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
        title: "Full Name",
        align: "start",
        sortable: false,
        key: "full_name",
    },
    {
        title: "Unique Identifier",
        align: "start",
        sortable: false,
        key: "unique_identifier",
    },
    {
        title: "Username",
        align: "start",
        sortable: false,
        key: "username",
    },
    {
        title: "Email Address",
        align: "start",
        sortable: false,
        key: "email",
    },
    {
        title: "Position",
        align: "start",
        sortable: false,
        key: "position",
    },
    {
        title: "Property",
        align: "start",
        sortable: false,
        key: "property_id",
    },
    {
        title: "Is Able to Login",
        align: "start",
        sortable: false,
        key: "is_able_to_login",
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
