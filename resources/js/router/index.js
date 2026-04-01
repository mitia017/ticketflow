// resources/js/router/index.js
import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";

const routes = [
    {
        path: "/login",
        name: "Login",
        component: () => import("../views/Login.vue"),
        meta: { guest: true },
    },
    { path: "/", redirect: "/dashboard" },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: () => import("../views/Dashboard.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/tickets",
        name: "Tickets",
        component: () => import("../views/Tickets/Index.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/tickets/:id",
        name: "TicketDetails",
        component: () => import("../views/Tickets/Show.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/login/google/callback",
        name: "GoogleCallback",
        component: () => import("../views/GoogleCallback.vue"),
        meta: { guest: true },
    }, //
];

const router = createRouter({ history: createWebHistory(), routes });

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Attendre que l'état d'authentification soit déterminé
    if (!authStore.initialized) {
        await authStore.checkAuth();
    }

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next("/login");
    } else if (to.meta.guest && authStore.isAuthenticated) {
        next("/dashboard");
    } else {
        next();
    }
});

export default router;
