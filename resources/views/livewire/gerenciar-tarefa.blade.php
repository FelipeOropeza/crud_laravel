<div class="p-4 max-w-4xl mx-auto">
    <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100">Lista de Tarefas</h2>

    <form wire:submit.prevent="adicionar" class="mb-6 flex flex-wrap gap-4 items-start">
        <div class="flex-1 min-w-[200px]">
            <x-input-label for="titulo" value="Título" />
            <x-text-input id="titulo" wire:model="novaTarefa.titulo" class="w-full" placeholder="Título da tarefa"
                :error="$errors->first('novaTarefa.titulo')" />
        </div>

        <div class="flex-1 min-w-[300px]">
            <x-input-label for="descricao" value="Descrição" />
            <x-text-input id="descricao" wire:model="novaTarefa.descricao" class="w-full" placeholder="Descrição breve"
                :error="$errors->first('novaTarefa.descricao')" />
        </div>

        <div class="w-40 min-w-[120px]">
            <x-input-label for="status" value="Status" />
            <select id="status" wire:model="novaTarefa.status"
                class="w-full rounded border px-3 py-2 bg-white dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Selecione</option>
                <option value="pendente">Pendente</option>
                <option value="em andamento">Em andamento</option>
                <option value="concluido">Concluído</option>
            </select>
            @error('novaTarefa.status')
                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 mt-6">
                Adicionar
            </button>
        </div>
    </form>
    <div class="mb-6">
        <input type="text" wire:model.live="search" placeholder="Pesquisar tarefas..."
            class="w-full rounded border px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>

    @if(count($tarefas) > 0)
        <ul class="space-y-3 text-gray-900 dark:text-gray-100">
            @foreach ($tarefas as $tarefa)
                <li class="border p-3 rounded flex justify-between items-start border-gray-300 dark:border-gray-700">
                    <div>
                        <strong>{{ $tarefa['titulo'] }}</strong> -
                        <em class="text-sm lowercase">{{ $tarefa['status'] ?? 'sem status' }}</em><br>
                        <span>{{ $tarefa['descricao'] }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="abrirModalEditar({{ $tarefa['id'] }})"
                            class="text-blue-500 hover:text-blue-700">🔄</button>
                        <button wire:click="remover({{ $tarefa['id'] }})" class="text-red-500 hover:text-red-700">🗑️</button>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500 dark:text-gray-400">Nenhuma tarefa adicionada.</p>
    @endif

    @if($modalAberto)
        <div x-data x-show="true" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div x-show="true"
                class="bg-white dark:bg-gray-900 rounded-lg shadow-lg max-w-lg w-full p-6 mx-4 transition-all transform">
                <h3 class="text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100">Editar Tarefa</h3>

                <form wire:submit.prevent="salvarAtualizacao" class="space-y-5">
                    <div>
                        <x-input-label for="tituloEdit" value="Título" />
                        <x-text-input id="tituloEdit" wire:model.defer="tarefaEditada.titulo" class="w-full"
                            :error="$errors->first('tarefaEditada.titulo')" />
                    </div>

                    <div>
                        <x-input-label for="descricaoEdit" value="Descrição" />
                        <textarea id="descricaoEdit" wire:model.defer="tarefaEditada.descricao" rows="4"
                            class="w-full rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 resize-y focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('tarefaEditada.descricao') border-red-500 dark:border-red-400 @enderror"></textarea>
                        @error('tarefaEditada.descricao')
                            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                x-transition class="mt-1 text-sm text-red-600 dark:text-red-400 min-h-[1.25rem]">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div>
                        <x-input-label for="statusEdit" value="Status" />
                        <select id="statusEdit" wire:model.defer="tarefaEditada.status"
                            class="w-full rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="pendente">Pendente</option>
                            <option value="concluida">Concluída</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="$set('modalAberto', false)"
                            class="px-5 py-2 rounded-md bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>