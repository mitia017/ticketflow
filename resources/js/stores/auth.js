// resources/js/stores/auth.js
import { defineStore } from "pinia";
import axios from "../utils/axios";
import router from "../router";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
        token: localStorage.getItem("token"),
        isAuthenticated: false,
        initialized: false, // <-- NOUVEAU
    }),

    getters: {
        isAdmin: (state) => state.user?.role === "admin",
        isAgent: (state) =>
            state.user?.role === "agent" || state.user?.role === "admin",
        isViewer: (state) => state.user?.role === "viewer",
        userName: (state) => state.user?.name,
    },

    actions: {
        async login(credentials) {
            try {
                const response = await axios.post("/auth/login", credentials);
                this.token = response.data.token;
                this.user = response.data.user;
                this.isAuthenticated = true;
                this.initialized = true;
                localStorage.setItem("token", response.data.token);
                axios.defaults.headers.common["Authorization"] =
                    `Bearer ${response.data.token}`;
                router.push("/dashboard");
                return { success: true };
            } catch (error) {
                return {
                    success: false,
                    error: error.response?.data?.message || "Login failed",
                };
            }
        },

        async logout() {
            try {
                await axios.post("/auth/logout");
            } catch (e) {}
            this.token = null;
            this.user = null;
            this.isAuthenticated = false;
            this.initialized = true;
            localStorage.removeItem("token");
            delete axios.defaults.headers.common["Authorization"];
            router.push("/login");
        },

        async checkAuth() {
            // Ne pas exécuter deux fois
            if (this.initialized) return;

            const token = localStorage.getItem("token");
            if (!token) {
                this.initialized = true;
                return;
            }

            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            try {
                const response = await axios.get("/auth/me");
                this.user = response.data;
                this.isAuthenticated = true;
                this.token = token;
            } catch (error) {
                console.error("checkAuth error:", error);
                localStorage.removeItem("token");
                delete axios.defaults.headers.common["Authorization"];
                this.token = null;
                this.user = null;
                this.isAuthenticated = false;
            } finally {
                this.initialized = true;
            }
        },
    },
});
