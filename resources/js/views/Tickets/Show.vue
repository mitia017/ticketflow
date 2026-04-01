<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <Navbar />
        <div class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-4">
                    <router-link
                        to="/tickets"
                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-colors inline-flex items-center gap-1"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Back to Tickets
                    </router-link>
                </div>

                <!-- État de chargement -->
                <div v-if="loading" class="text-center py-12">
                    <div
                        class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 dark:border-primary-400"
                    ></div>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        Loading ticket...
                    </p>
                </div>

                <!-- Message d'erreur -->
                <div
                    v-else-if="error"
                    class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg"
                >
                    <p>{{ error }}</p>
                    <button
                        @click="retryLoad"
                        class="mt-2 btn-secondary dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"
                    >
                        Retry
                    </button>
                </div>

                <!-- Ticket chargé -->
                <div
                    v-else-if="ticket"
                    class="grid grid-cols-1 lg:grid-cols-3 gap-6"
                >
                    <!-- Colonne principale -->
                    <div class="lg:col-span-2 space-y-6">
                        <div
                            class="bg-gray-100 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors"
                        >
                            <div class="flex justify-between items-start mb-4">
                                <h1
                                    class="text-2xl font-bold text-gray-900 dark:text-white"
                                >
                                    {{ ticket.title }}
                                </h1>
                                <button
                                    v-if="can.editTicket(ticket)"
                                    @click="showEditModal = true"
                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors"
                                >
                                    Edit
                                </button>
                            </div>
                            <p
                                class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap"
                            >
                                {{ ticket.description }}
                            </p>
                            <div class="mt-4 flex gap-2">
                                <span
                                    :class="statusClass(ticket.status)"
                                    class="px-2.5 py-0.5 text-xs font-semibold rounded-full"
                                >
                                    {{ ticket.status }}
                                </span>
                                <span
                                    :class="priorityClass(ticket.priority)"
                                    class="px-2.5 py-0.5 text-xs font-semibold rounded-full"
                                >
                                    {{ ticket.priority }}
                                </span>
                            </div>
                        </div>

                        <!-- Commentaires -->
                        <div
                            class="bg-gray-100 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors"
                        >
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                            >
                                Comments
                            </h3>
                            <div
                                class="space-y-4 mb-6 max-h-96 overflow-y-auto"
                            >
                                <div
                                    v-for="comment in ticket.comments"
                                    :key="comment.id"
                                    class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0"
                                >
                                    <div
                                        class="flex justify-between items-start mb-2"
                                    >
                                        <span
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                comment.user?.name || "Unknown"
                                            }}
                                        </span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{ formatDate(comment.created_at) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ comment.content }}
                                    </p>
                                </div>
                                <div
                                    v-if="!ticket.comments?.length"
                                    class="text-gray-500 dark:text-gray-400 text-center py-4"
                                >
                                    No comments yet.
                                </div>
                            </div>

                            <form @submit.prevent="addComment" class="mt-4">
                                <textarea
                                    v-model="newComment"
                                    rows="3"
                                    placeholder="Add a comment..."
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors"
                                ></textarea>
                                <div class="mt-2 flex justify-end">
                                    <button
                                        type="submit"
                                        :disabled="
                                            !newComment.trim() || commentLoading
                                        "
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:focus:ring-offset-gray-800"
                                    >
                                        <svg
                                            v-if="commentLoading"
                                            class="animate-spin h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <span
                                            class="text-gray-900 dark:text-gray-200"
                                            >{{
                                                commentLoading
                                                    ? "Posting..."
                                                    : "Post Comment"
                                            }}</span
                                        >
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <div
                            class="bg-gray-100 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors"
                        >
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                            >
                                Ticket Details
                            </h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Created By
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 dark:text-white"
                                    >
                                        {{ ticket.creator?.name || "Unknown" }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Created At
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 dark:text-white"
                                    >
                                        {{ formatDateTime(ticket.created_at) }}
                                    </dd>
                                </div>
                                <div v-if="ticket.closed_at">
                                    <dt
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Closed At
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 dark:text-white"
                                    >
                                        {{ formatDateTime(ticket.closed_at) }}
                                    </dd>
                                </div>
                                <div v-if="can.assignTicket()">
                                    <dt
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Assigned To
                                    </dt>
                                    <dd class="mt-1">
                                        <select
                                            v-model="assignedTo"
                                            @change="updateAssignment"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors"
                                        >
                                            <option :value="null">
                                                Unassigned
                                            </option>
                                            <option
                                                v-for="agent in agents"
                                                :key="agent.id"
                                                :value="agent.id"
                                            >
                                                {{ agent.name }}
                                            </option>
                                        </select>
                                    </dd>
                                </div>
                                <div v-if="can.editTicket(ticket)">
                                    <dt
                                        class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Status
                                    </dt>
                                    <dd class="mt-1">
                                        <select
                                            v-model="status"
                                            @change="updateStatus"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors"
                                        >
                                            <option value="open">Open</option>
                                            <option value="in_progress">
                                                In Progress
                                            </option>
                                            <option value="resolved">
                                                Resolved
                                            </option>
                                            <option value="closed">
                                                Closed
                                            </option>
                                        </select>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <EditTicketModal
            v-if="showEditModal"
            :ticket="ticket"
            @close="showEditModal = false"
            @updated="refreshTicket"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useTicketsStore } from "../../stores/tickets";
import { usePermission } from "../../composables/usePermission";
import { useWebSocket } from "../../composables/useWebSocket";
import Navbar from "../../components/Navbar.vue";
import EditTicketModal from "../../components/EditTicketModal.vue";
import axios from "../../utils/axios";
import { format } from "date-fns";

const route = useRoute();
const router = useRouter();
const store = useTicketsStore();
const { can } = usePermission();

const ticket = ref(null);
const agents = ref([]);
const assignedTo = ref(null);
const status = ref("");
const newComment = ref("");
const showEditModal = ref(false);
const loading = ref(true);
const error = ref(null);
const commentLoading = ref(false);

const formatDate = (date) => format(new Date(date), "MMM d, yyyy h:mm a");
const formatDateTime = (date) => format(new Date(date), "MMM d, yyyy h:mm a");

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
        low: "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300",
        medium: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300",
        high: "bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300",
        critical:
            "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300",
    };
    return (
        classes[priority] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
    );
};

const loadTicket = async () => {
    loading.value = true;
    error.value = null;
    try {
        const id = route.params.id;
        const data = await store.fetchTicket(id);
        if (data) {
            ticket.value = data;
            assignedTo.value = data.assigned_to?.id || null;
            status.value = data.status;
        } else {
            error.value = "Ticket not found.";
        }
    } catch (err) {
        console.error("Load ticket error:", err);
        error.value = "Failed to load ticket. Please try again.";
    } finally {
        loading.value = false;
    }
};

const loadAgents = async () => {
    try {
        const response = await axios.get("/agents");
        agents.value = response.data;
    } catch (error) {
        console.error("Failed to load agents:", error);
    }
};

const addComment = async () => {
    if (!newComment.value.trim()) return;
    commentLoading.value = true;
    const result = await store.addComment(ticket.value.id, newComment.value);
    commentLoading.value = false;
    if (result.success) {
        newComment.value = "";
    } else {
        alert(result.error || "Failed to post comment.");
    }
};

const updateAssignment = async () => {
    await store.updateTicket(ticket.value.id, {
        assigned_to: assignedTo.value,
    });
};

const updateStatus = async () => {
    await store.updateTicket(ticket.value.id, { status: status.value });
};

const refreshTicket = () => {
    loadTicket();
    showEditModal.value = false;
};

const retryLoad = () => {
    loadTicket();
};

// WebSocket - désactivée si pas de clé Pusher pour éviter les erreurs
let wsInstance = null;
if (import.meta.env.VITE_PUSHER_APP_KEY) {
    const { initializeWebSocket, disconnect } = useWebSocket(
        route.params.id,
        (comment) => {
            if (ticket.value) {
                if (!ticket.value.comments) ticket.value.comments = [];
                ticket.value.comments.unshift(comment);
            }
        },
    );
    wsInstance = { initializeWebSocket, disconnect };
}

onMounted(async () => {
    await loadTicket();
    await loadAgents();
    if (wsInstance) {
        wsInstance.initializeWebSocket();
    }
});

onUnmounted(() => {
    if (wsInstance) {
        wsInstance.disconnect();
    }
});
</script>
