<x-app-layout>
    <x-slot name="title">{{ $project->title }}</x-slot>

<div class="px-4 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800">← Voltar para Projetos</a>
    </div>

    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Criado por: {{ $project->owner->name }}</p>
                </div>
                @if($project->user_id === auth()->id())
                <div class="flex gap-2">
                    <a href="{{ route('projects.edit', $project) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        Editar
                    </a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este projeto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                            Excluir
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Data de Início</p>
                    <p class="text-gray-900">{{ $project->start_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Data Prevista de Conclusão</p>
                    <p class="text-gray-900">{{ $project->expected_end_date->format('d/m/Y') }}</p>
                </div>
            </div>

            @if($project->description)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-1">Descrição</p>
                <p class="text-gray-900">{{ $project->description }}</p>
            </div>
            @endif

            @if($project->attachment)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-1">Anexo</p>
                <a href="{{ Storage::url($project->attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    Ver arquivo anexado
                </a>
            </div>
            @endif
        </div>
    </div>

    @if($project->user_id === auth()->id())
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Adicionar Membro</h2>
            <form action="{{ route('projects.add-member', $project) }}" method="POST" class="flex gap-2">
                @csrf
                <input type="email" name="email" placeholder="Email do usuário" required
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Adicionar
                </button>
            </form>
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    @endif

    @if($project->members->count() > 0)
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Membros do Projeto</h2>
            <div class="space-y-2">
                @foreach($project->members as $member)
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium">{{ $member->name }}</p>
                        <p class="text-sm text-gray-500">{{ $member->email }}</p>
                    </div>
                    @if($project->user_id === auth()->id())
                    <form action="{{ route('projects.remove-member', [$project, $member]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                            Remover
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Tarefas do Projeto</h2>
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center" title="Criar nova tarefa neste projeto">
                    <i class="fas fa-plus mr-2"></i>Nova Tarefa
                </a>
            </div>

            @if($project->tasks->count() > 0)
            <div class="space-y-3">
                @foreach($project->tasks as $task)
                <div class="border-l-4 @if($task->status === 'concluida') border-green-500 @elseif($task->status === 'em_andamento') border-yellow-500 @else border-blue-500 @endif pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-500">
                                Status: <span class="capitalize">{{ str_replace('_', ' ', $task->status) }}</span> |
                                Prioridade: <span class="capitalize">{{ $task->priority }}</span> |
                                Vencimento: {{ $task->due_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Ver
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-gray-500 py-4">Nenhuma tarefa cadastrada neste projeto.</p>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
