<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Meus Projetos
            </h2>
            <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Novo Projeto
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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
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
                            <div class="mt-4 flex space-x-2">
                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver</a>
                                <a href="{{ route('projects.edit', $project) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Editar</a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este projeto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Excluir</button>
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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition border-l-4 border-green-500">
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
                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver Projeto</a>
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
