<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div
                class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
            ></div>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Connexion en cours...
            </p>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import axios from "../utils/axios";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

onMounted(async () => {
    const token = route.query.token;
    const userParam = route.query.user;

    if (token && userParam) {
        try {
            // Décoder l'utilisateur (c'est du JSON)
            const user = JSON.parse(decodeURIComponent(userParam));

            // Stocker le token
            localStorage.setItem("token", token);
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

            // Mettre à jour le store
            authStore.user = user;
            authStore.token = token;
            authStore.isAuthenticated = true;

            // Rediriger vers le dashboard
            router.push("/dashboard");
        } catch (error) {
            console.error(
                "Erreur lors du traitement du callback Google",
                error,
            );
            router.push("/login?error=google_auth_failed");
        }
    } else {
        // Pas de token ou utilisateur => rediriger vers login
        router.push("/login");
    }
});
</script>
