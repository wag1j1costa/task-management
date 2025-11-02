<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Buscar tarefas criadas pelo usuário OU atribuídas a ele
        $query = Task::where(function($q) {
            $q->where('user_id', auth()->id())
              ->orWhere('assigned_to', auth()->id());
        })->with('project', 'assignedTo');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->latest()->paginate(12);
        $projects = auth()->user()->projects;

        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create()
    {
        $projects = auth()->user()->projects;
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $query = Task::where('user_id', auth()->id())
                        ->where('title', $value);

                    if ($request->filled('project_id')) {
                        $query->where('project_id', $request->project_id);
                    } else {
                        $query->whereNull('project_id');
                    }

                    if ($query->exists()) {
                        $fail('Já existe uma tarefa com este título' . ($request->filled('project_id') ? ' neste projeto.' : '.'));
                    }
                }
            ],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'in:baixa,media,alta'],
            'status' => ['required', 'in:pendente,em_andamento,concluida'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        if ($validated['project_id']) {
            $project = Project::findOrFail($validated['project_id']);
            if (!$project->isMember(auth()->user())) {
                abort(403, 'Você não tem permissão para adicionar tarefas neste projeto.');
            }
        }

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('tasks', 'public');
        }

        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $task)
    {
        if (!$task->canAccess(auth()->user())) {
            abort(403, 'Você não tem permissão para acessar esta tarefa.');
        }

        $task->load('project', 'assignedTo', 'user');

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if (!$task->canAccess(auth()->user())) {
            abort(403, 'Você não tem permissão para editar esta tarefa.');
        }

        $projects = auth()->user()->projects;
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        if (!$task->canAccess(auth()->user())) {
            abort(403, 'Você não tem permissão para editar esta tarefa.');
        }

        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $task) {
                    $query = Task::where('user_id', auth()->id())
                        ->where('title', $value)
                        ->where('id', '!=', $task->id);

                    if ($request->filled('project_id')) {
                        $query->where('project_id', $request->project_id);
                    } else {
                        $query->whereNull('project_id');
                    }

                    if ($query->exists()) {
                        $fail('Já existe uma tarefa com este título' . ($request->filled('project_id') ? ' neste projeto.' : '.'));
                    }
                }
            ],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'in:baixa,media,alta'],
            'status' => ['required', 'in:pendente,em_andamento,concluida'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        if ($validated['project_id']) {
            $project = Project::findOrFail($validated['project_id']);
            if (!$project->isMember(auth()->user())) {
                abort(403, 'Você não tem permissão para associar tarefas a este projeto.');
            }
        }

        if ($request->hasFile('attachment')) {
            if ($task->attachment) {
                Storage::disk('public')->delete($task->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('tasks', 'public');
        }

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para excluir esta tarefa.');
        }

        if ($task->attachment) {
            Storage::disk('public')->delete($task->attachment);
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa excluída com sucesso!');
    }

    public function complete(Task $task)
    {
        if (!$task->canAccess(auth()->user())) {
            abort(403, 'Você não tem permissão para completar esta tarefa.');
        }

        $task->update(['status' => 'concluida']);

        return back()->with('success', 'Tarefa marcada como concluída!');
    }
}
