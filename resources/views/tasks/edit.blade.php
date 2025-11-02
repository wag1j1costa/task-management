<x-app-layout>
    <x-slot name="title">Editar Tarefa</x-slot>

<div class="px-4 sm:px-0">
    <div class="mb-8">
        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800">← Voltar para a Tarefa</a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Tarefa</h1>

            <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título *</label>
                        <input type="text" name="title" id="title" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('title', $task->title) }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700">Projeto (opcional)</label>
                        <select name="project_id" id="project_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sem projeto</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700">Atribuir a (Opcional)</label>
                        <select name="assigned_to" id="assigned_to"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Ninguém</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                    @if($user->id === auth()->id()) - Eu @endif
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Data de Vencimento *</label>
                            <input type="date" name="due_date" id="due_date" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Prioridade *</label>
                            <select name="priority" id="priority" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="baixa" {{ old('priority', $task->priority) === 'baixa' ? 'selected' : '' }}>Baixa</option>
                                <option value="media" {{ old('priority', $task->priority) === 'media' ? 'selected' : '' }}>Média</option>
                                <option value="alta" {{ old('priority', $task->priority) === 'alta' ? 'selected' : '' }}>Alta</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pendente" {{ old('status', $task->status) === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="em_andamento" {{ old('status', $task->status) === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="concluida" {{ old('status', $task->status) === 'concluida' ? 'selected' : '' }}>Concluída</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if($task->attachment)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Anexo Atual</label>
                        <a href="{{ Storage::url($task->attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            Ver arquivo anexado
                        </a>
                    </div>
                    @endif

                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700">{{ $task->attachment ? 'Substituir Anexo' : 'Adicionar Anexo' }}</label>
                        <input type="file" name="attachment" id="attachment"
                               accept=".pdf"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">Formato: PDF. Tamanho máximo: 5MB</p>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Salvar Alterações
                    </button>
                    <a href="{{ route('tasks.show', $task) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
