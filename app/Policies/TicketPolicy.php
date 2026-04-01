<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket)
    {
        return $user->role === 'admin'
            || ($user->role === 'agent' && $ticket->assigned_to === $user->id)
            || $ticket->created_by === $user->id;
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->role === 'admin' || ($user->role === 'agent' && $ticket->assigned_to === $user->id) || $ticket->created_by === $user->id;
    }

    public function comment(User $user, Ticket $ticket)
    {
        return $this->view($user, $ticket);
    }

    public function delete(User $user, Ticket $ticket)
    {
        return $user->role === 'admin';
    }
}
