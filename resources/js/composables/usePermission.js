import { useAuthStore } from '../stores/auth';

export function usePermission() {
  const authStore = useAuthStore();
  const can = {
    viewAllTickets: () => authStore.isAdmin || authStore.isAgent,
    editTicket: (ticket) => {
      if (authStore.isAdmin) return true;
      if (authStore.isAgent && ticket.assigned_to?.id === authStore.user?.id) return true;
      if (ticket.created_by?.id === authStore.user?.id) return true;
      return false;
    },
    assignTicket: () => authStore.isAdmin || authStore.isAgent,
    deleteTicket: () => authStore.isAdmin,
    viewDashboard: () => authStore.isAdmin || authStore.isAgent,
    exportTickets: () => authStore.isAdmin || authStore.isAgent,
  };
  return { can };
}
