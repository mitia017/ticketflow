<template>
    <nav class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1
                        class="text-2xl font-bold text-primary-600 dark:text-primary-400"
                    >
                        TicketFlow
                    </h1>
                    <div class="text-sm ml-6 space-x-4">
                        <router-link
                            to="/dashboard"
                            class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400"
                            >Dashboard</router-link
                        >
                        <router-link
                            to="/tickets"
                            class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400"
                            >Tickets</router-link
                        >
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button
                        @click="toggleTheme"
                        class="relative inline-flex items-center justify-center w-12 h-6 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 bg-gray-300 dark:bg-gray-600"
                        aria-label="Changer de thème"
                    >
                        <span
                            class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-200 flex items-center justify-center"
                            :class="
                                themeStore.isDark
                                    ? 'translate-x-6'
                                    : 'translate-x-0'
                            "
                        >
                            <svg
                                v-if="themeStore.isDark"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-3.5 h-3.5 text-gray-800"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 21v-2.25m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 21v-2.25m-6.364-.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591"
                                />
                            </svg>
                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-3.5 h-3.5 text-gray-800"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"
                                />
                            </svg>
                        </span>
                    </button>
                    <button
                        @click="logout"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200 bg-red-600 text-white hover:bg-red-700 hover:scale-105 hover:shadow-md dark:bg-red-800 dark:hover:bg-red-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                            />
                        </svg>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useAuthStore } from "../stores/auth";
import { useThemeStore } from "../stores/theme";

const authStore = useAuthStore();
const themeStore = useThemeStore();
const logout = () => authStore.logout();
const toggleTheme = () => themeStore.toggleTheme();
</script>
