<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <Navbar />
        <div class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h1
                        class="text-3xl font-bold text-gray-900 dark:text-white"
                    >
                        Tickets
                    </h1>
                    <button
                        @click="showCreate = true"
                        class="btn-primary text-gray-900 dark:text-gray-200"
                    >
                        Create Ticket
                    </button>
                </div>

                <!-- Filters (déjà compatibles) -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4"
                >
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 transition-colors"
                        >
                            <option value="">All statuses</option>
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>

                        <select
                            v-model="filters.priority"
                            @change="applyFilters"
                            class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 transition-colors"
                        >
                            <option value="">All priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>

                        <div class="relative w-full max-w-md">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                            >
                                <svg
                                    class="w-5 h-5 text-gray-400 dark:text-gray-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>
                            </div>
                            <input
                                v-model="filters.search"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Rechercher un ticket..."
                                class="w-full py-2 pl-10 pr-10 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-500 transition-colors"
                            />
                            <button
                                v-if="filters.search"
                                @click="
                                    filters.search = '';
                                    debouncedSearch();
                                "
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none"
                                aria-label="Effacer la recherche"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        <button
                            @click="resetFilters"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-400 transition-colors"
                        >
                            Reset Filters
                        </button>
                    </div>
                </div>

                <!-- Tickets Table -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden"
                >
                    <div v-if="loading" class="p-8 text-center">
                        <div
                            class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
                        ></div>
                    </div>
                    <table
                        v-else
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"
                                >
                                    ID
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"
                                >
                                    Title
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"
                                >
                                    Priority
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"
                                >
                                    Assigned To
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"
                                >
                                    Created
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <tr
                                v-for="ticket in tickets"
                                :key="ticket.id"
                                @click="viewTicket(ticket.id)"
                                class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                >
                                    #{{ ticket.id }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100"
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
                                        :class="priorityClass(ticket.priority)"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    >
                                        {{ ticket.priority }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200"
                                >
                                    {{ ticket.assignee?.name || "Unassigned" }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ formatDate(ticket.created_at) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                >
                                    <button
                                        @click.stop="viewTicket(ticket.id)"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                                    >
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div
                        class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6"
                    >
                        <div
                            class="flex justify-between items-center text-gray-900 dark:text-gray-300"
                        >
                            <button
                                @click="prevPage"
                                :disabled="page === 1"
                                class="btn-secondary"
                            >
                                Previous
                            </button>
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300"
                            >
                                Page {{ page }} of {{ lastPage }}
                            </span>
                            <button
                                @click="nextPage"
                                :disabled="page === lastPage"
                                class="btn-secondary"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <CreateTicketModal
            v-if="showCreate"
            @close="showCreate = false"
            @created="refresh"
        />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useTicketsStore } from "../../stores/tickets";
import Navbar from "../../components/Navbar.vue";
import CreateTicketModal from "../../components/CreateTicketModal.vue";
import { format } from "date-fns";
import { debounce } from "lodash";

const router = useRouter();
const store = useTicketsStore();
const showCreate = ref(false);

const tickets = computed(() => store.tickets);
const loading = computed(() => store.loading);
const page = computed(() => store.pagination.current_page);
const lastPage = computed(() => store.pagination.last_page);
const filters = computed({
    get: () => store.filters,
    set: (value) => store.setFilter(value.key, value.value),
});

const formatDate = (date) => format(new Date(date), "MMM d, yyyy");

const statusClass = (status) => {
    const classes = {
        open: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300",
        in_progress:
            "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300",
        resolved:
            "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300",
        closed: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
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

const applyFilters = () => store.fetchTickets(1);
const debouncedSearch = debounce(() => store.fetchTickets(1), 500);
const resetFilters = () => store.resetFilters();
const viewTicket = (id) => router.push(`/tickets/${id}`);
const prevPage = () => {
    if (page.value > 1) store.fetchTickets(page.value - 1);
};
const nextPage = () => {
    if (page.value < lastPage.value) store.fetchTickets(page.value + 1);
};
const refresh = () => store.fetchTickets(page.value);

store.fetchTickets();
</script>
