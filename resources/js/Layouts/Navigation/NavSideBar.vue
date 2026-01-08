<template>
    <v-navigation-drawer v-model="drawer" app color="primary">
        <!-- USER INFO -->
        <v-sheet class="pa-4 d-flex flex-column align-center" color="#000020">
            <v-avatar class="mb-4" size="53">
                <v-icon :icon="profileIcon" size="x-large" color="white" />
            </v-avatar>
            <div class="user-name">John Doe Doorman</div>
            <div class="user-email">johndoedoorman@example.com</div>
        </v-sheet>

        <v-divider />

        <!-- SIDEBAR -->
        <v-list nav v-model:opened="opened">
            <!-- DASHBOARD -->
            <Link href="/" class="no-underline-v-list-item">
                <v-list-item
                    :active="false"
                    class="sidebar-hover"
                    :class="{ selected: page.url === '/' }"
                >
                    <template #prepend>
                        <v-icon icon="mdi-home" />
                    </template>
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item>
            </Link>

            <!-- SYSTEMS GROUP -->
            <v-list-group value="systems">
                <template #activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-tools"
                        title="Systems"
                        class="sidebar-hover"
                        :class="{ selected: isSystemRoute }"
                    />
                </template>

                <!-- SYSTEM LINKS -->
                <Link
                    v-for="link in systemLinks"
                    :key="link.module"
                    :href="`/${moduleLink(link.module)}`"
                    class="no-underline-v-list-item"
                >
                    <v-list-item
                        :active="false"
                        class="sidebar-hover"
                        :class="{
                            selected:
                                page.url === `/${moduleLink(link.module)}`,
                        }"
                    >
                        <v-list-item-title>
                            {{ moduleName(link.module) }}
                        </v-list-item-title>
                    </v-list-item>
                </Link>
            </v-list-group>
        </v-list>

        <!-- FOOTER -->
        <template #append>
            <v-footer class="text-center">
                <strong>{{ appName }}</strong>
                <br />
                Developer © — {{ new Date().getFullYear() }}
            </v-footer>
        </template>
    </v-navigation-drawer>

    <!-- TOP NAV -->
    <nav-bar :hasDrawer="true" @toggleDrawer="toggleDrawer" />
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { useDisplay } from "vuetify";
import NavBar from "./NavBar.vue";

/* DRAWER */
const drawer = ref(false);
const opened = ref(["systems"]); // 👈 keep Systems open

const { mobile } = useDisplay();
onMounted(() => {
    if (!mobile.value) drawer.value = true;
});
const toggleDrawer = () => (drawer.value = !drawer.value);

/* PAGE */
const page = usePage();
const appName = computed(() => page.props?.appName ?? "App Name");

/* USER ICON */
const profileIcon = computed(() =>
    page.props?.auth?.user?.isAdmin ? "mdi-account-star" : "mdi-account"
);

/* SYSTEM LINKS */
const systemLinks = [
    { module: "profiles" },
    { module: "employees" },
    { module: "departments" },
    { module: "properties" },
    { module: "locations" },
    { module: "scan_histories" },
];

/* HELPERS */
const moduleLink = (m) => m.replace(/_/g, "-");
const moduleName = (m) =>
    m
        .replace(/_/g, "-")
        .split("-")
        .map((w) => w[0].toUpperCase() + w.slice(1))
        .join(" ");

/* IS SYSTEM ROUTE */
const isSystemRoute = computed(() =>
    systemLinks.some((l) => page.url === `/${moduleLink(l.module)}`)
);
</script>

<style scoped>
.user-name,
.user-email {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    text-align: center;
    max-width: 100%;
    display: inline-block;
    white-space: normal;
    color: white; /* ✅ Default name/email is white */
}

/* Style for the user's name */
.user-name {
    font-weight: bold;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

/* Style for the user's email */
.user-email {
    font-size: 0.9rem;
}

/* Smaller screens */
@media (max-width: 600px) {
    .user-name {
        font-size: 0.8rem;
    }
    .user-email {
        font-size: 0.8rem;
    }
}

/* Larger screens */
@media (min-width: 1200px) {
    .user-name {
        font-size: 1rem;
    }
    .user-email {
        font-size: 0.9rem;
    }
}

/* Selected state (stays active) */
.sidebar-hover.selected {
    background-color: #000020 !important;
    border-radius: 6px;
}
.sidebar-hover.selected .v-list-item-title,
.sidebar-hover.selected .v-icon {
    color: white !important;
}

/* ✅ Click/press only (temporary, not staying) */
.sidebar-hover:active {
    background-color: #000020 !important;
    border-radius: 6px;
}
.sidebar-hover:active .v-list-item-title,
.sidebar-hover:active .v-icon {
    color: black !important;
}

/* Hover effect */
.sidebar-hover:hover {
    background-color: #808080;
    transition: background-color 0.3s ease, color 0.3s ease;
    border-radius: 6px;
    cursor: pointer;
}
.sidebar-hover:hover .v-list-item-title,
.sidebar-hover:hover .v-icon {
    color: white !important;
}

/* NEW CSS for custom ripple color */
.sidebar-hover :deep(.v-ripple__container) {
    color: white !important;
}

/* Remove underline from Inertia's Link */
.no-underline-v-list-item {
    text-decoration: none !important;
}

/* ✅ Make ALL sidebar text and icons white by default */
.no-underline-v-list-item .v-list-item-title,
.no-underline-v-list-item .v-icon {
    color: white !important;
}
</style>
