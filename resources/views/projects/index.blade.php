<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2 sm:mb-0">
                Meus Projetos
            </h2>
            <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center justify-center" title="Criar novo projeto">
                <i class="fas fa-plus mr-2"></i>Novo Projeto
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

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Projetos Criados por Mim</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($projects as $project)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition border border-gray-200 shadow-lg">
                        <div class="p-6">
                            <h4 class="font-bold text-lg mb-2">
                                <a href="{{ route('projects.show', $project) }}" class="text-gray-900 hover:text-blue-600">
                                    {{ $project->title }}
                                </a>
                            </h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $project->description ?? 'Sem descrição' }}
                            </p>
                            <div class="text-xs text-gray-500 space-y-1">
                                <div>Início: {{ $project->start_date->format('d/m/Y') }}</div>
                                <div>Conclusão prevista: {{ $project->expected_end_date->format('d/m/Y') }}</div>
                                <div>Tarefas: {{ $project->tasks->count() }}</div>
                                <div>Membros: {{ $project->members->count() + 1 }}</div>
                            </div>
                            <div class="mt-4 flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 p-3 sm:p-2 rounded-lg hover:bg-blue-50 transition-colors text-center" title="Ver projeto">
                                    <i class="fas fa-eye mr-1 sm:mr-0"></i><span class="sm:hidden">Ver Projeto</span>
                                </a>
                                <a href="{{ route('projects.edit', $project) }}" class="text-yellow-600 hover:text-yellow-800 p-3 sm:p-2 rounded-lg hover:bg-yellow-50 transition-colors text-center" title="Editar projeto">
                                    <i class="fas fa-edit mr-1 sm:mr-0"></i><span class="sm:hidden">Editar</span>
                                </a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline w-full sm:w-auto" onsubmit="return confirm('Tem certeza que deseja excluir este projeto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-3 sm:p-2 rounded-lg hover:bg-red-50 transition-colors w-full sm:w-auto" title="Excluir projeto">
                                        <i class="fas fa-trash mr-1 sm:mr-0"></i><span class="sm:hidden">Excluir</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        Você ainda não criou nenhum projeto.
                    </div>
                    @endforelse
                </div>
                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            </div>

            @if($sharedProjects->count() > 0)
            <div>
                <h3 class="text-lg font-semibold mb-4">Projetos Compartilhados Comigo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sharedProjects as $project)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition border-l-4 border-2 border-green-500 shadow-lg">
                        <div class="p-6">
                            <h4 class="font-bold text-lg mb-2">
                                <a href="{{ route('projects.show', $project) }}" class="text-gray-900 hover:text-blue-600">
                                    {{ $project->title }}
                                </a>
                            </h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $project->description ?? 'Sem descrição' }}
                            </p>
                            <div class="text-xs text-gray-500 space-y-1">
                                <div>Criador: {{ $project->user->name }}</div>
                                <div>Início: {{ $project->start_date->format('d/m/Y') }}</div>
                                <div>Conclusão prevista: {{ $project->expected_end_date->format('d/m/Y') }}</div>
                                <div>Tarefas: {{ $project->tasks->count() }}</div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors inline-flex items-center" title="Ver projeto">
                                    <i class="fas fa-eye mr-2"></i>Ver Projeto
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
