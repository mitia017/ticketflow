<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $baseQuery = Ticket::query();

        // Appliquer le filtre selon le rôle
        if ($request->user()->role === 'viewer') {
            $baseQuery->where('created_by', $request->user()->id);
        } elseif ($request->user()->role === 'agent') {
            $baseQuery->where('assigned_to', $request->user()->id);
        }

        $totalTickets = (clone $baseQuery)->count();
        $openTickets = (clone $baseQuery)->where('status', 'open')->count();
        $inProgressTickets = (clone $baseQuery)->where('status', 'in_progress')->count();
        $resolvedTickets = (clone $baseQuery)->where('status', 'resolved')->count();
        $closedTickets = (clone $baseQuery)->where('status', 'closed')->count();

        $priorityStats = [
            'low' => (clone $baseQuery)->where('priority', 'low')->count(),
            'medium' => (clone $baseQuery)->where('priority', 'medium')->count(),
            'high' => (clone $baseQuery)->where('priority', 'high')->count(),
            'critical' => (clone $baseQuery)->where('priority', 'critical')->count(),
        ];

        // Tickets par jour (derniers 7 jours)
        $ticketsByDay = (clone $baseQuery)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Temps moyen de résolution (tickets de l'utilisateur)
        $avgResolutionTime = 0;
        $resolvedQuery = (clone $baseQuery)->whereNotNull('closed_at');
        if ($resolvedQuery->count() > 0) {
            $totalHours = $resolvedQuery->select(
                DB::raw('AVG((julianday(closed_at) - julianday(created_at)) * 24) as avg_hours')
            )->value('avg_hours');
            $avgResolutionTime = round($totalHours ?? 0, 2);
        }

        // Performance des agents : pour un agent, on affiche ses propres stats
        if ($request->user()->role === 'agent') {
            $agentPerformance = User::where('id', $request->user()->id)
                ->withCount(['assignedTickets as total_assigned'])
                ->withCount(['assignedTickets as resolved_count' => function ($q) {
                    $q->whereIn('status', ['resolved', 'closed']);
                }])
                ->get()
                ->map(function ($agent) {
                    return [
                        'name' => $agent->name,
                        'assigned' => $agent->total_assigned,
                        'resolved' => $agent->resolved_count,
                        'resolution_rate' => $agent->total_assigned > 0
                            ? round(($agent->resolved_count / $agent->total_assigned) * 100, 2)
                            : 0,
                    ];
                });
        } else {
            // Pour l'admin, on garde la liste de tous les agents
            $agentPerformance = User::where('role', 'agent')
                ->withCount(['assignedTickets as total_assigned'])
                ->withCount(['assignedTickets as resolved_count' => function ($q) {
                    $q->whereIn('status', ['resolved', 'closed']);
                }])
                ->get()
                ->map(function ($agent) {
                    return [
                        'name' => $agent->name,
                        'assigned' => $agent->total_assigned,
                        'resolved' => $agent->resolved_count,
                        'resolution_rate' => $agent->total_assigned > 0
                            ? round(($agent->resolved_count / $agent->total_assigned) * 100, 2)
                            : 0,
                    ];
                });
        }

        return response()->json([
            'total' => $totalTickets,
            'by_status' => [
                'open' => $openTickets,
                'in_progress' => $inProgressTickets,
                'resolved' => $resolvedTickets,
                'closed' => $closedTickets,
            ],
            'by_priority' => $priorityStats,
            'tickets_by_day' => $ticketsByDay,
            'avg_resolution_hours' => $avgResolutionTime,
            'agent_performance' => $agentPerformance,
        ]);
    }
}
