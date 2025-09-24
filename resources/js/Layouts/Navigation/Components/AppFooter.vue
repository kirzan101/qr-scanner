<template>
    <v-footer
        app
        color="transparent"
        :absolute="true"
        class="d-flex justify-center text-center"
        height="54"
    >
        <div>
            <strong>{{ `${appName} v${appVersion}` }}</strong>

            <br />

            <strong>{{ appDeveloper }} &copy;</strong> —
            {{ new Date().getFullYear() }}
        </div>
    </v-footer>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const appName = computed(() => {
    return page.props.appName ?? "App Name";
});

const appVersion = computed(() => {
    return page.props.appVersion;
});

const appDeveloper = computed(() => {
    return page.props.appDeveloper ?? "Developer";
});
</script>
