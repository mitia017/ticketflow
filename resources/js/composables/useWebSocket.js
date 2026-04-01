import { ref, onUnmounted } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export function useWebSocket(ticketId, onNewComment) {
  const echo = ref(null);
  const channel = ref(null);

  const initializeWebSocket = () => {
    window.Pusher = Pusher;
    echo.value = new Echo({
      broadcaster: 'pusher',
      key: import.meta.env.VITE_PUSHER_APP_KEY,
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
      forceTLS: true,
    });
    channel.value = echo.value.channel(`ticket.${ticketId}`);
    channel.value.listen('CommentAdded', (data) => {
      if (onNewComment) onNewComment(data.comment);
    });
  };

  const disconnect = () => {
    if (channel.value) { channel.value.stopListening('CommentAdded'); channel.value = null; }
    if (echo.value) { echo.value.disconnect(); echo.value = null; }
  };

  onUnmounted(() => disconnect());
  return { initializeWebSocket, disconnect };
}
