<x-app-layout>
    <x-slot name="title">Editar Projeto</x-slot>

<div class="px-4 sm:px-0">
    <div class="mb-8">
        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">← Voltar para o Projeto</a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar Projeto</h1>

            <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título *</label>
                        <input type="text" name="title" id="title" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('title', $project->title) }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Data de Início *</label>
                            <input type="date" name="start_date" id="start_date" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expected_end_date" class="block text-sm font-medium text-gray-700">Data Prevista de Conclusão *</label>
                            <input type="date" name="expected_end_date" id="expected_end_date" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('expected_end_date', $project->expected_end_date->format('Y-m-d')) }}">
                            @error('expected_end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if($project->attachment)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Anexo Atual</label>
                        <a href="{{ Storage::url($project->attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            Ver arquivo anexado
                        </a>
                    </div>
                    @endif

                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700">{{ $project->attachment ? 'Substituir Anexo' : 'Adicionar Anexo' }}</label>
                        <input type="file" name="attachment" id="attachment"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">Formatos: PDF, JPG, PNG. Tamanho máximo: 5MB</p>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Salvar Alterações
                    </button>
                    <a href="{{ route('projects.show', $project) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
