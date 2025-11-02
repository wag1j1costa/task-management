<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

<div class="px-4 sm:px-0">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card Tarefas Pendentes -->
        <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg hover:scale-105 cursor-pointer"
             onclick="window.location.href='{{ route('tasks.index') }}?status=pendente'">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tarefas Pendentes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $pendingTasks }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Em Andamento -->
        <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg hover:scale-105 cursor-pointer"
             onclick="window.location.href='{{ route('tasks.index') }}?status=em_andamento'">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Em Andamento</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $inProgressTasks }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Concluídas -->
        <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg hover:scale-105 cursor-pointer"
             onclick="window.location.href='{{ route('tasks.index') }}?status=concluida'">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Concluídas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $completedTasks }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Projetos -->
        <div class="bg-white overflow-hidden shadow rounded-lg transition-all duration-300 hover:shadow-lg hover:scale-105 cursor-pointer"
             onclick="window.location.href='{{ route('projects.index') }}'">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Projetos</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalProjects + $memberProjectsCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($overdueTasks->count() > 0)
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Tarefas Atrasadas</h2>
            <div class="space-y-3">
                @foreach($overdueTasks as $task)
                <div class="border-l-4 border-red-500 pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-500">
                                Vencimento: {{ $task->due_date->format('d/m/Y') }}
                                @if($task->project)
                                    | Projeto: {{ $task->project->title }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Ver detalhes da tarefa">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Tarefas Recentes</h2>
                <a href="{{ route('tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center" title="Criar nova tarefa">
                    <i class="fas fa-plus mr-2"></i>Nova Tarefa
                </a>
            </div>
            @if($recentTasks->count() > 0)
            <div class="space-y-3">
                @foreach($recentTasks as $task)
                <div class="border-l-4 @if($task->status === 'concluida') border-green-500 @elseif($task->status === 'em_andamento') border-yellow-500 @else border-blue-500 @endif pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-500">
                                Status: <span class="capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                                | Vencimento: {{ $task->due_date->format('d/m/Y') }}
                                @if($task->project)
                                    | Projeto: {{ $task->project->title }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Ver tarefa">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">Nenhuma tarefa cadastrada ainda.</p>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
