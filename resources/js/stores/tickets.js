import { defineStore } from 'pinia';
import axios from '../utils/axios';

export const useTicketsStore = defineStore('tickets', {
  state: () => ({
    tickets: [],
    currentTicket: null,
    loading: false,
    pagination: { current_page: 1, last_page: 1, per_page: 15, total: 0 },
    filters: { status: '', priority: '', assigned_to: '', search: '', date_from: '', date_to: '' },
  }),
  actions: {
    async fetchTickets(page = 1) {
      this.loading = true;
      try {
        const params = { page, ...this.filters };
        const res = await axios.get('/tickets', { params });
        this.tickets = res.data.data;
        this.pagination = {
          current_page: res.data.current_page,
          last_page: res.data.last_page,
          per_page: res.data.per_page,
          total: res.data.total,
        };
      } catch (error) { console.error(error); } finally { this.loading = false; }
    },
    async createTicket(data) {
      try {
        const res = await axios.post('/tickets', data);
        await this.fetchTickets(this.pagination.current_page);
        return { success: true, ticket: res.data };
      } catch (error) { return { success: false, error: error.response?.data?.message }; }
    },
    async updateTicket(id, data) {
      try {
        const res = await axios.put(`/tickets/${id}`, data);
        const idx = this.tickets.findIndex(t => t.id === id);
        if (idx !== -1) this.tickets[idx] = res.data;
        if (this.currentTicket?.id === id) this.currentTicket = res.data;
        return { success: true, ticket: res.data };
      } catch (error) { return { success: false, error: error.response?.data?.message }; }
    },
    async fetchTicket(id) {
      try {
        const res = await axios.get(`/tickets/${id}`);
        this.currentTicket = res.data;
        return res.data;
      } catch (error) { console.error(error); return null; }
    },
    async addComment(ticketId, content) {
      try {
        const res = await axios.post(`/tickets/${ticketId}/comments`, { content });
        if (this.currentTicket && this.currentTicket.id === ticketId) {
          if (!this.currentTicket.comments) this.currentTicket.comments = [];
          this.currentTicket.comments.unshift(res.data);
        }
        return { success: true, comment: res.data };
      } catch (error) { return { success: false, error: error.response?.data?.message }; }
    },
    setFilter(key, value) { this.filters[key] = value; this.fetchTickets(1); },
    resetFilters() {
      this.filters = { status: '', priority: '', assigned_to: '', search: '', date_from: '', date_to: '' };
      this.fetchTickets(1);
    },
  },
});
