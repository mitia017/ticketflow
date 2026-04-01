<template>
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4"
    >
        <div class="w-full max-w-md">
            <!-- Carte principale -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden"
            >
                <!-- En-tête avec onglets -->
                <div class="flex border-b border-gray-200 dark:border-gray-700">
                    <button
                        @click="activeTab = 'login'"
                        :class="[
                            'flex-1 py-4 text-center font-medium transition-colors',
                            activeTab === 'login'
                                ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400'
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300',
                        ]"
                    >
                        Sign In
                    </button>
                    <button
                        @click="activeTab = 'register'"
                        :class="[
                            'flex-1 py-4 text-center font-medium transition-colors',
                            activeTab === 'register'
                                ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400'
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300',
                        ]"
                    >
                        Join Us
                    </button>
                </div>

                <!-- Contenu -->
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{
                                activeTab === "login"
                                    ? "Welcome back"
                                    : "Create an account"
                            }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            {{
                                activeTab === "login"
                                    ? "Sign in to continue"
                                    : "Start managing tickets with TicketFlow"
                            }}
                        </p>
                    </div>

                    <!-- Formulaire de connexion -->
                    <form
                        v-if="activeTab === 'login'"
                        @submit.prevent="handleLogin"
                    >
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >Email</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="loginForm.email"
                                        type="email"
                                        required
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        placeholder="your@email.com"
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >Password</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15v2m-6-4h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4V8a4 4 0 00-8 0v3h8z"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="loginForm.password"
                                        :type="
                                            showPassword ? 'text' : 'password'
                                        "
                                        required
                                        class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        placeholder="••••••••"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    >
                                        <svg
                                            v-if="!showPassword"
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input
                                        id="remember"
                                        v-model="remember"
                                        type="checkbox"
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                    />
                                    <label
                                        for="remember"
                                        class="ml-2 block text-sm text-gray-700 dark:text-gray-300"
                                        >Remember me</label
                                    >
                                </div>
                                <a
                                    href="#"
                                    class="text-sm text-primary-600 dark:text-primary-400 hover:underline"
                                    >Forgot password?</a
                                >
                            </div>

                            <div
                                v-if="loginError"
                                class="text-red-600 dark:text-red-400 text-sm text-center bg-red-50 dark:bg-red-900/20 p-2 rounded"
                            >
                                {{ loginError }}
                            </div>

                            <button
                                type="submit"
                                :disabled="loginLoading"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50"
                            >
                                {{ loginLoading ? "Signing in..." : "Login" }}
                            </button>

                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div
                                        class="w-full border-t border-gray-300 dark:border-gray-600"
                                    ></div>
                                </div>
                                <div
                                    class="relative flex justify-center text-sm"
                                ></div>
                            </div>
                        </div>
                    </form>

                    <!-- Formulaire d'inscription -->
                    <form v-else @submit.prevent="handleRegister">
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >Full name</label
                                >
                                <input
                                    v-model="registerForm.name"
                                    type="text"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="John Doe"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >Email</label
                                >
                                <input
                                    v-model="registerForm.email"
                                    type="email"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="your@email.com"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >Password</label
                                >
                                <input
                                    v-model="registerForm.password"
                                    type="password"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="••••••••"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                    >Confirm password</label
                                >
                                <input
                                    v-model="registerForm.password_confirmation"
                                    type="password"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="••••••••"
                                />
                            </div>

                            <div
                                v-if="registerError"
                                class="text-red-600 dark:text-red-400 text-sm text-center bg-red-50 dark:bg-red-900/20 p-2 rounded"
                            >
                                {{ registerError }}
                            </div>

                            <button
                                type="submit"
                                :disabled="registerLoading"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50"
                            >
                                {{
                                    registerLoading
                                        ? "Creating account..."
                                        : "Sign Up"
                                }}
                            </button>
                        </div>
                    </form>

                    <p
                        class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400"
                    >
                        {{
                            activeTab === "login"
                                ? "Don't have an account? "
                                : "Already have an account? "
                        }}
                        <button
                            @click="
                                activeTab =
                                    activeTab === 'login' ? 'register' : 'login'
                            "
                            class="text-primary-600 dark:text-primary-400 hover:underline"
                        >
                            {{ activeTab === "login" ? "Join Us" : "Sign In" }}
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "../stores/auth";
import axios from "../utils/axios";
import { useRoute } from "vue-router";

const authStore = useAuthStore();
const activeTab = ref("login"); // 'login' ou 'register'
const route = useRoute();
const error = route.query.error;
if (error === "google_auth_failed") {
    loginError.value = "Google authentication failed. Please try again.";
}

// Login form
const loginForm = ref({ email: "", password: "" });
const remember = ref(false);
const loginLoading = ref(false);
const loginError = ref("");
const showPassword = ref(false);

// Register form
const registerForm = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});
const registerLoading = ref(false);
const registerError = ref("");

const handleLogin = async () => {
    loginLoading.value = true;
    loginError.value = "";
    const result = await authStore.login(loginForm.value);
    if (!result.success) {
        loginError.value = result.error || "Invalid credentials";
    }
    loginLoading.value = false;
};

const handleRegister = async () => {
    registerLoading.value = true;
    registerError.value = "";

    // Simple client-side validation
    if (
        registerForm.value.password !== registerForm.value.password_confirmation
    ) {
        registerError.value = "Passwords do not match";
        registerLoading.value = false;
        return;
    }

    try {
        const response = await axios.post("/auth/register", {
            name: registerForm.value.name,
            email: registerForm.value.email,
            password: registerForm.value.password,
            password_confirmation: registerForm.value.password_confirmation,
        });
        // Auto-login after successful registration
        await authStore.login({
            email: registerForm.value.email,
            password: registerForm.value.password,
        });
    } catch (error) {
        registerError.value =
            error.response?.data?.message || "Registration failed";
    } finally {
        registerLoading.value = false;
    }
};
</script>
