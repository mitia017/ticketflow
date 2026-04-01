<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentAdded;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Ticket::with(['creator', 'assignee']);

        // Filtres (inchangés)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filtres selon le rôle
        if ($request->user()->role === 'viewer') {
            $query->where('created_by', $request->user()->id);
        } elseif ($request->user()->role === 'agent') {
            $query->where('assigned_to', $request->user()->id);
        }

        $tickets = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['title' => 'required|string|max:255', 'description' => 'required|string', 'priority' => 'required|in:low,medium,high,critical']);
        $ticket = Ticket::create(['title' => $validated['title'], 'description' => $validated['description'], 'priority' => $validated['priority'], 'created_by' => $request->user()->id, 'status' => 'open']);

        return response()->json($ticket->load(['creator', 'assignee']), 201);
    }

    public function show(Ticket $ticket)
    {
        try {
            $this->authorize('view', $ticket);
            $ticket->load(['creator', 'assignee', 'comments.user']);

            return response()->json($ticket);
        } catch (\Exception $e) {
            \Log::error('Ticket show error: '.$e->getMessage());

            return response()->json(['error' => 'Unable to load ticket'], 500);
        }
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $validated = $request->validate(['status' => 'sometimes|in:open,in_progress,resolved,closed', 'assigned_to' => 'sometimes|exists:users,id', 'priority' => 'sometimes|in:low,medium,high,critical']);
        if (isset($validated['status']) && $validated['status'] === 'closed') {
            $validated['closed_at'] = now();
        }
        $ticket->update($validated);
        if (isset($validated['assigned_to']) && $validated['assigned_to'] !== $ticket->assigned_to) {
            Notification::create(['user_id' => $validated['assigned_to'], 'title' => 'New Ticket Assignment', 'message' => "You have been assigned to ticket #{$ticket->id}: {$ticket->title}", 'data' => ['ticket_id' => $ticket->id]]);
        }

        return response()->json($ticket->load(['creator', 'assignee']));
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $this->authorize('comment', $ticket);
        $validated = $request->validate(['content' => 'required|string']);
        $comment = $ticket->comments()->create(['content' => $validated['content'], 'user_id' => $request->user()->id]);
        $comment->load('user');
        event(new CommentAdded($ticket, $comment));
        if ($ticket->assigned_to && $ticket->assigned_to !== $request->user()->id) {
            Notification::create(['user_id' => $ticket->assigned_to, 'title' => 'New Comment', 'message' => "New comment on ticket #{$ticket->id}: {$ticket->title}", 'data' => ['ticket_id' => $ticket->id, 'comment_id' => $comment->id]]);
        }

        return response()->json($comment, 201);
    }

    public function export(Request $request)
    {
        $query = Ticket::with(['creator', 'assignee']);
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        $tickets = $query->get();
        $csv = "ID,Title,Status,Priority,Created By,Assigned To,Created At,Closed At\n";
        foreach ($tickets as $ticket) {
            $csv .= "{$ticket->id},{$ticket->title},{$ticket->status},{$ticket->priority},";
            $csv .= "{$ticket->creator->name},";
            $csv .= ($ticket->assignee ? $ticket->assignee->name : 'Unassigned').',';
            $csv .= "{$ticket->created_at},";
            $csv .= ($ticket->closed_at ? $ticket->closed_at : '')."\n";
        }

        return response($csv, 200)->header('Content-Type', 'text/csv')->header('Content-Disposition', 'attachment; filename="tickets_export.csv"');
    }
}
