<template>
    <Head :title="title" />
    <MainLayout :errors="errors" :flash="flash" :can="can">
        <v-container>
            <LocationContent :flash="flash" :errors="errors" :can="can" />
        </v-container>
    </MainLayout>
</template>
<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

//components
import MainLayout from "../../Layouts/MainLayout.vue";
import LocationContent from "../../Components/Pages/System/Location/LocationContent.vue";

defineProps({
    errors: Object,
    flash: Object,
    can: Array,
});

const page = usePage();
const appName = computed(() => {
    return page.props.appName ?? "App Name";
});

const title = computed(() => {
    return `Locations — ${appName.value}`;
});
</script>
