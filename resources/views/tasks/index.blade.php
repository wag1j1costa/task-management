<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2 sm:mb-0">
                Minhas Tarefas
            </h2>
            <a href="{{ route('tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center justify-center" title="Criar nova tarefa">
                <i class="fas fa-plus mr-2"></i>Nova Tarefa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="em_andamento" {{ request('status') === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="concluida" {{ request('status') === 'concluida' ? 'selected' : '' }}>Concluída</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                            <select name="priority" id="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todas</option>
                                <option value="baixa" {{ request('priority') === 'baixa' ? 'selected' : '' }}>Baixa</option>
                                <option value="media" {{ request('priority') === 'media' ? 'selected' : '' }}>Média</option>
                                <option value="alta" {{ request('priority') === 'alta' ? 'selected' : '' }}>Alta</option>
                            </select>
                        </div>

                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Projeto</label>
                            <select name="project_id" id="project_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($tasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($tasks as $task)
                        <div class="border-l-4 border-{{ $task->status === 'concluida' ? 'green' : ($task->status === 'em_andamento' ? 'blue' : 'yellow') }}-500 pl-4 py-3 hover:bg-gray-50 {{ $task->isOverdue() ? 'bg-red-50' : '' }}">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                                <div class="flex-1 mb-3 sm:mb-0">
                                    <a href="{{ route('tasks.show', $task) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                        {{ $task->title }}
                                    </a>
                                    @if($task->isOverdue())
                                    <span class="ml-2 text-red-600 text-sm font-semibold">⚠️ ATRASADA</span>
                                    @endif
                                    <div class="text-sm text-gray-600 mt-1">
                                        @if($task->project)
                                        <span class="mr-2">Projeto: {{ $task->project->title }}</span>
                                        <span class="mx-2">•</span>
                                        @endif
                                        Vencimento: {{ $task->due_date->format('d/m/Y') }}
                                        @if($task->assignedTo)
                                        <span class="mx-2">•</span>
                                        <span class="mr-2">Atribuída a:
                                            @if($task->assigned_to === auth()->id())
                                                <span class="font-medium text-blue-600">Você</span>
                                            @else
                                                {{ $task->assignedTo->name }}
                                            @endif
                                        </span>
                                        @endif
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-{{ $task->priority === 'alta' ? 'red' : ($task->priority === 'media' ? 'yellow' : 'green') }}-100 text-{{ $task->priority === 'alta' ? 'red' : ($task->priority === 'media' ? 'yellow' : 'green') }}-800">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 ml-4 sm:ml-4">
                                    @if($task->status !== 'concluida')
                                    <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 p-3 sm:p-2 rounded-lg hover:bg-green-50 transition-colors w-full sm:w-auto" title="Concluir tarefa">
                                            <i class="fas fa-check mr-1 sm:mr-0"></i><span class="sm:hidden">Concluir</span>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:text-blue-800 p-3 sm:p-2 rounded-lg hover:bg-blue-50 transition-colors text-center" title="Editar tarefa">
                                        <i class="fas fa-edit mr-1 sm:mr-0"></i><span class="sm:hidden">Editar</span>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline w-full sm:w-auto" onsubmit="return confirm('Excluir esta tarefa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-3 sm:p-2 rounded-lg hover:bg-red-50 transition-colors w-full sm:w-auto" title="Excluir tarefa">
                                            <i class="fas fa-trash mr-1 sm:mr-0"></i><span class="sm:hidden">Excluir</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $tasks->links() }}
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-12">Nenhuma tarefa encontrada com os filtros selecionados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
