<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects()
            ->with('members', 'tasks')
            ->latest()
            ->paginate(9);

        $sharedProjects = auth()->user()->memberProjects()
            ->with('user', 'tasks')
            ->latest()
            ->get();

        return view('projects.index', compact('projects', 'sharedProjects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'expected_end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('projects', 'public');
        }

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projeto criado com sucesso!');
    }

    public function show(Project $project)
    {
        if (!$project->isMember(auth()->user())) {
            abort(403, 'Você não tem permissão para acessar este projeto.');
        }

        $project->load('tasks', 'members', 'owner');

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Apenas o dono pode editar o projeto.');
        }

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Apenas o dono pode editar o projeto.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'expected_end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('attachment')) {
            if ($project->attachment) {
                Storage::disk('public')->delete($project->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Apenas o dono pode excluir o projeto.');
        }

        if ($project->attachment) {
            Storage::disk('public')->delete($project->attachment);
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Projeto excluído com sucesso!');
    }

    public function addMember(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Apenas o dono pode adicionar membros.');
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user->id === $project->user_id) {
            return back()->withErrors(['email' => 'Você já é o dono do projeto.']);
        }

        if ($project->members->contains($user->id)) {
            return back()->withErrors(['email' => 'Usuário já é membro do projeto.']);
        }

        $project->members()->attach($user->id);

        return back()->with('success', 'Membro adicionado com sucesso!');
    }

    public function removeMember(Project $project, User $user)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Apenas o dono pode remover membros.');
        }

        $project->members()->detach($user->id);

        return back()->with('success', 'Membro removido com sucesso!');
    }
}
