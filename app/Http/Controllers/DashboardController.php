<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $pendingTasks = $user->tasks()
            ->where('status', 'pendente')
            ->count();

        $inProgressTasks = $user->tasks()
            ->where('status', 'em_andamento')
            ->count();

        $completedTasks = $user->tasks()
            ->where('status', 'concluida')
            ->count();

        $overdueTasks = $user->tasks()
            ->where('status', '!=', 'concluida')
            ->where('due_date', '<', now())
            ->with('project')
            ->latest()
            ->get();

        $recentTasks = $user->tasks()
            ->with('project')
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
