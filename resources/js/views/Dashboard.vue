<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <Navbar />
        <div class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-8"
                >
                    Dashboard
                </h1>

                <<!-- 4 cartes KPI -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <!-- Total tickets -->
                    <div
                        class="bg-blue-50 dark:bg-gray-800 rounded-lg shadow p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="bg-blue-100 dark:bg-blue-900 rounded-lg p-3"
                            >
                                <svg
                                    class="h-6 w-6 text-blue-600 dark:text-blue-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                    ></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ stats.total || 0 }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Total Tickets
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Open tickets -->
                    <div
                        class="bg-yellow-50 dark:bg-gray-800 rounded-lg shadow p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="bg-yellow-100 dark:bg-yellow-900 rounded-lg p-3"
                            >
                                <svg
                                    class="h-6 w-6 text-yellow-600 dark:text-yellow-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ stats.by_status?.open || 0 }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Open
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Resolved tickets -->
                    <div
                        class="bg-green-50 dark:bg-gray-800 rounded-lg shadow p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="bg-green-100 dark:bg-green-900 rounded-lg p-3"
                            >
                                <svg
                                    class="h-6 w-6 text-green-600 dark:text-green-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ stats.by_status?.resolved || 0 }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Resolved
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Critical priority tickets -->
                    <div
                        class="bg-red-50 dark:bg-gray-800 rounded-lg shadow p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="bg-red-100 dark:bg-red-900 rounded-lg p-3"
                            >
                                <svg
                                    class="h-6 w-6 text-red-600 dark:text-red-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-3xl font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ stats.by_priority?.critical || 0 }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Critical
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div
                        class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-4"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Tickets by Status
                        </h3>
                        <div class="h-64 flex items-center justify-center">
                            <canvas ref="statusChartRef"></canvas>
                        </div>
                    </div>
                    <div
                        class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-4"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        >
                            Tickets per Day (Last 7 days)
                        </h3>
                        <div class="h-64">
                            <canvas ref="lineChartRef"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tableau des tickets récents -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8"
                >
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white"
                        >
                            Recent Tickets
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-400 uppercase"
                                    >
                                        ID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-400 uppercase"
                                    >
                                        Title
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-400 uppercase"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-400 uppercase"
                                    >
                                        Priority
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-gray-400 uppercase"
                                    >
                                        Created
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                            >
                                <tr
                                    v-for="ticket in recentTickets"
                                    :key="ticket.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                    @click="viewTicket(ticket.id)"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                    >
                                        {{ ticket.id }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-gray-900 dark:text-white"
                                    >
                                        {{ ticket.title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="statusClass(ticket.status)"
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            {{ ticket.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="
                                                priorityClass(ticket.priority)
                                            "
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            {{ ticket.priority }}
                                        </span>
                                    </td>

                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ formatDate(ticket.created_at) }}
                                    </td>
                                </tr>
                                <tr v-if="recentTickets.length === 0">
                                    <td
                                        colspan="5"
                                        class="px-6 py-4 text-center text-gray-500 dark:text-gray-400"
                                    >
                                        No tickets found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Agent Performance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white"
                        >
                            Agent Performance
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                                    >
                                        Agent
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                                    >
                                        Assigned
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                                    >
                                        Resolved
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"
                                    >
                                        Resolution Rate
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                            >
                                <tr
                                    v-for="agent in stats.agent_performance"
                                    :key="agent.name"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ agent.name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-400"
                                    >
                                        {{ agent.assigned }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-400"
                                    >
                                        {{ agent.resolved }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-400"
                                    >
                                        <span
                                            :class="
                                                agent.resolution_rate >= 70
                                                    ? 'text-green-600 dark:text-green-400'
                                                    : 'text-yellow-600 dark:text-yellow-400'
                                            "
                                        >
                                            {{ agent.resolution_rate }}%
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import { Chart, registerables } from "chart.js";
import { useThemeStore } from "../stores/theme";
import Navbar from "../components/Navbar.vue";
import axios from "../utils/axios";
import { format } from "date-fns";

Chart.register(...registerables);
const router = useRouter();
const themeStore = useThemeStore();

const stats = ref({});
const recentTickets = ref([]);
const statusChartRef = ref(null);
const lineChartRef = ref(null);
let statusChart = null;
let lineChart = null;

const statusClass = (status) => {
    const classes = {
        open: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
        in_progress:
            "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200",
        resolved:
            "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
        closed: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
    };
    return (
        classes[status] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
    );
};
const priorityClass = (priority) => {
    const classes = {
        low: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
        medium: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200",
        high: "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200",
        critical: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
    };
    return (
        classes[priority] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
    );
};

const formatDate = (date) => format(new Date(date), "MMM d, yyyy");

const viewTicket = (id) => router.push(`/tickets/${id}`);

const fetchStats = async () => {
    try {
        const response = await axios.get("/dashboard/stats");
        stats.value = response.data;
        updateCharts();
    } catch (error) {
        console.error("Failed to fetch stats:", error);
    }
};

const fetchRecentTickets = async () => {
    try {
        const response = await axios.get("/tickets?per_page=5");
        recentTickets.value = response.data.data;
    } catch (error) {
        console.error("Failed to fetch recent tickets:", error);
    }
};

const updateCharts = () => {
    if (statusChart) statusChart.destroy();
    if (lineChart) lineChart.destroy();

    // Couleurs en fonction du thème
    const textColor = themeStore.isDark ? "#e5e7eb" : "#111827";
    const gridColor = themeStore.isDark ? "#374151" : "#e5e7eb";

    // Graphique en anneau
    statusChart = new Chart(statusChartRef.value, {
        type: "doughnut",
        data: {
            labels: ["Open", "In Progress", "Resolved", "Closed"],
            datasets: [
                {
                    data: [
                        stats.value.by_status?.open || 0,
                        stats.value.by_status?.in_progress || 0,
                        stats.value.by_status?.resolved || 0,
                        stats.value.by_status?.closed || 0,
                    ],
                    backgroundColor: [
                        "#3b82f6",
                        "#eab308",
                        "#10b981",
                        "#6b7280",
                    ],
                    borderWidth: 0, // Supprime les bordures pour un rendu plus propre
                    hoverOffset: 8, // Effet "pop-out" au survol
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: "70%", // Crée le trou central (doughnut)
            plugins: {
                legend: {
                    position: "bottom",
                    labels: { color: textColor },
                },
                tooltip: {
                    bodyColor: textColor, // Tu peux ajouter d'autres options ici
                },
            },
            // Optionnel : ajoute une ombre portée sur tout le canvas (via un plugin ou en CSS)
            // Si tu veux une ombre douce, ajoute ce bloc après l'initialisation du chart :
            // statusChart.canvas.parentNode.style.filter = "drop-shadow(0 4px 8px rgba(0,0,0,0.1))";
        },
    });
    // Helper function to create a gradient (light to dark)
    function createGradient(colorLight, colorDark) {
        const ctx = statusChartRef.value.getContext("2d");
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, colorLight);
        gradient.addColorStop(1, colorDark);
        return gradient;
    }

    // Graphique linéaire
    const ticketsByDay = stats.value.tickets_by_day || [];
    const labels = ticketsByDay.map((item) =>
        format(new Date(item.date), "MMM d"),
    );
    const dataPoints = ticketsByDay.map((item) => item.total);

    lineChart = new Chart(lineChartRef.value, {
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "Tickets created",
                    data: dataPoints,
                    borderColor: "#a855f7",
                    backgroundColor: "rgba(168, 85, 247, 0.1)",
                    fill: true,
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: "top", labels: { color: textColor } },
                tooltip: { bodyColor: textColor },
            },
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor } },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: textColor },
                    grid: { color: gridColor },
                },
            },
        },
    });
};

// Reconstruire les graphiques si le thème change
watch(
    () => themeStore.isDark,
    () => {
        if (stats.value.total !== undefined) updateCharts();
    },
);

onMounted(() => {
    fetchStats();
    fetchRecentTickets();
});
</script>
