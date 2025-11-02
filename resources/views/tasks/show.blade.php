<x-app-layout>
    <x-slot name="title">{{ $task->title }}</x-slot>

<div class="px-4 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:text-blue-800">← Voltar para Tarefas</a>
    </div>

    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <div class="flex gap-2 mt-2">
                        <span class="px-2 py-1 rounded text-sm @if($task->status === 'concluida') bg-green-100 text-green-800 @elseif($task->status === 'em_andamento') bg-yellow-100 text-yellow-800 @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                        <span class="px-2 py-1 rounded text-sm @if($task->priority === 'alta') bg-red-100 text-red-800 @elseif($task->priority === 'media') bg-yellow-100 text-yellow-800 @else bg-gray-100 text-gray-800 @endif">
                            Prioridade: {{ ucfirst($task->priority) }}
                        </span>
                        @if($task->isOverdue())
                        <span class="px-2 py-1 rounded text-sm bg-red-100 text-red-800 font-semibold">
                            ATRASADA
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-2 gap-2">
                    @if($task->status !== 'concluida')
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors w-full sm:w-auto" title="Marcar como concluída">
                            <i class="fas fa-check mr-2"></i>Concluir
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors text-center" title="Editar tarefa">
                        <i class="fas fa-edit mr-2"></i>Editar
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors w-full sm:w-auto" title="Excluir tarefa">
                            <i class="fas fa-trash mr-2"></i>Excluir
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Criado por</p>
                    <p class="text-gray-900">{{ $task->user->name }}</p>
                </div>
                @if($task->assignedTo)
                <div>
                    <p class="text-sm font-medium text-gray-700">Atribuído a</p>
                    <p class="text-gray-900">{{ $task->assignedTo->name }} ({{ $task->assignedTo->email }})</p>
                </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-gray-700">Data de Vencimento</p>
                    <p class="text-gray-900">{{ $task->due_date->format('d/m/Y') }}</p>
                </div>
                @if($task->project)
                <div>
                    <p class="text-sm font-medium text-gray-700">Projeto</p>
                    <a href="{{ route('projects.show', $task->project) }}" class="text-blue-600 hover:text-blue-800">
                        {{ $task->project->title }}
                    </a>
                </div>
                @endif
            </div>

            @if($task->description)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-1">Descrição</p>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $task->description }}</p>
            </div>
            @endif

            @if($task->attachment)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-1">Anexo</p>
                <a href="{{ Storage::url($task->attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    Ver arquivo anexado (PDF)
                </a>
            </div>
            @endif

            <div class="border-t pt-4 mt-4">
                <p class="text-sm text-gray-500">
                    Criada em: {{ $task->created_at->format('d/m/Y H:i') }}
                </p>
                @if($task->updated_at != $task->created_at)
                <p class="text-sm text-gray-500">
                    Última atualização: {{ $task->updated_at->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
