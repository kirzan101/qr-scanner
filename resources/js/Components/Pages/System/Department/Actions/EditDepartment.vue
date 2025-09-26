<template>
    <v-btn-edit
        v-if="showBtn && can.includes('update')"
        :label="department.name"
        @click="toggleDialog"
    />
    <v-chip v-else variant="tonal" rounded>
        <v-tooltip
            class="ma-1"
            activator="parent"
            location="bottom"
            text="Unauthorized to edit"
        ></v-tooltip>
        {{ department.name.toUpperCase() }}
    </v-chip>
    <v-dialog
        v-model="dialog"
        width="1000"
        title="Edit Department"
        prependIcon="mdi-pencil-circle"
        persistent
        :btnDisabled="btnDisabled"
        @close="toggleDialog"
        @submit="handleSubmit"
    >
        <v-container>
            <FormDepartment
                :department="department"
                :errors="errors"
                :flash="flash"
                :can="can"
                @formValues="getFormDepartmentValue"
                ref="formDepartmentRef"
            />
        </v-container>
    </v-dialog>

    <SnackBar ref="snackBarRef" />
</template>

<script setup>
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

import FormDepartment from "./Forms/FormDepartment.vue";
import SnackBar from "@/Components/Utilities/SnackBar.vue";

const dialog = ref(false);
const toggleDialog = () => {
    dialog.value = !dialog.value;
};

const props = defineProps({
    department: {
        type: Object,
        required: true,
    },
    showBtn: {
        type: Boolean,
        default: true,
    },
    errors: Object,
    flash: Object,
    can: Array,
});

const form = ref({});

// Department form
const getFormDepartmentValue = (value) => {
    Object.assign(form.value, value);
};

const formDepartmentRef = ref(null);
const toggleFormDepartmentRef = () => {
    if (formDepartmentRef.value) {
        formDepartmentRef.value.emitFormData();
    }
};

// notification
const snackBarRef = ref(null);
const toggleSnackBar = (message, color) => {
    if (!snackBarRef.value) {
        return;
    }

    snackBarRef.value.showNotification(message, color);
};

// handle submission
const btnDisabled = ref(false);
const handleSubmit = () => {
    toggleFormDepartmentRef();

    // submission here
    router.post(
        `/departments/${props.department.id}`,
        {
            _method: "PUT",
            forceFormData: true,
            ...form.value,
        },
        {
            onSuccess: ({ props }) => {
                dialog.value = false;

                toggleSnackBar(props.flash.success, "accent");
            },
            onError: () => {
                toggleSnackBar("Some fields has an error.", "error");
            },
            onBefore: () => {
                btnDisabled.value = true;
            },
            onFinish: () => {
                btnDisabled.value = false;
            },
        }
    );
};

defineExpose({
    toggleDialog,
});
</script>
