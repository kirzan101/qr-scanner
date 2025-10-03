<template>
    <Head :title="title" />
    <MainLayout :errors="errors" :flash="flash" :can="can">
        <v-container>
            <ProfileContent
                :departments="departments"
                :positions="positions"
                :properties="properties"
                :flash="flash"
                :errors="errors"
                :can="can"
            />
        </v-container>
    </MainLayout>
</template>
<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

//components
import MainLayout from "../../Layouts/MainLayout.vue";
import ProfileContent from "../../Components/Pages/System/Profile/ProfileContent.vue";

defineProps({
    departments: Array,
    positions: Array,
    properties: Array,
    errors: Object,
    flash: Object,
    can: Array,
});

const page = usePage();
const appName = computed(() => {
    return page.props.appName ?? "App Name";
});

const title = computed(() => {
    return `Profiles — ${appName.value}`;
});
</script>
