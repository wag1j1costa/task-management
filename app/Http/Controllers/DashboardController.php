<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Buscar tarefas criadas pelo usuário OU atribuídas a ele
        $pendingTasks = Task::where(function($q) {
            $q->where('user_id', auth()->id())
              ->orWhere('assigned_to', auth()->id());
        })->where('status', 'pendente')
            ->count();

        $inProgressTasks = Task::where(function($q) {
            $q->where('user_id', auth()->id())
              ->orWhere('assigned_to', auth()->id());
        })->where('status', 'em_andamento')
            ->count();

        $completedTasks = Task::where(function($q) {
            $q->where('user_id', auth()->id())
              ->orWhere('assigned_to', auth()->id());
        })->where('status', 'concluida')
            ->count();

        $overdueTasks = Task::where(function($q) {
            $q->where('user_id', auth()->id())
              ->orWhere('assigned_to', auth()->id());
        })->where('status', '!=', 'concluida')
            ->where('due_date', '<', now())
            ->with('project')
            ->latest()
            ->get();

        $recentTasks = Task::where(function($q) {
            $q->where('user_id', auth()->id())
              ->orWhere('assigned_to', auth()->id());
        })->with('project')
            ->latest()
            ->limit(5)
            ->get();

        $totalProjects = $user->projects()->count();
        $memberProjectsCount = $user->memberProjects()->count();

        return view('dashboard', compact(
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks',
            'recentTasks',
            'totalProjects',
            'memberProjectsCount'
        ));
    }
}
