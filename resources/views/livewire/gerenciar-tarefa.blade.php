<div class="p-4 max-w-4xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Lista de Tarefas</h2>

    <form wire:submit.prevent="adicionar" class="mb-6 flex flex-wrap gap-4 items-end">
        <!-- Título -->
        <div class="flex-1 min-w-[200px]">
            <label for="titulo" class="block text-sm font-medium mb-1">Título</label>
            <input type="text" id="titulo" wire:model="novaTarefa.titulo"
                class="w-full border rounded px-3 py-2 @error('novaTarefa.titulo') border-red-500 @enderror"
                placeholder="Título da tarefa" />
            @error('novaTarefa.titulo')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                     x-transition class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Descrição -->
        <div class="flex-1 min-w-[300px]">
            <label for="descricao" class="block text-sm font-medium mb-1">Descrição</label>
            <input type="text" id="descricao" wire:model="novaTarefa.descricao"
                class="w-full border rounded px-3 py-2 @error('novaTarefa.descricao') border-red-500 @enderror"
                placeholder="Descrição breve" />
            @error('novaTarefa.descricao')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                     x-transition class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Status -->
        <div class="w-40 min-w-[120px]">
            <label for="status" class="block text-sm font-medium mb-1">Status</label>
            <select id="status" wire:model="novaTarefa.status"
                class="w-full border rounded px-3 py-2 bg-white @error('novaTarefa.status') border-red-500 @enderror">
                <option value="">Selecione</option>
                <option value="pendente">Pendente</option>
                <option value="em andamento">Em andamento</option>
                <option value="concluido">Concluído</option>
            </select>
            @error('novaTarefa.status')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                     x-transition class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Botão -->
        <div class="flex items-center">
            <button type="submit"
                class="bg-blue-500 text-white px-5 py-2 rounded hover:bg-blue-600 transition">
                Adicionar
            </button>
        </div>
    </form>

    @if(count($tarefas) > 0)
        <ul class="space-y-3">
            @foreach ($tarefas as $index => $tarefa)
                <li class="border p-3 rounded flex justify-between items-start">
                    <div>
                        <strong>{{ $tarefa['titulo'] }}</strong> -
                        <em class="text-sm lowercase">{{ $tarefa['status'] ?? 'sem status' }}</em>
                        <br>
                        <span>{{ $tarefa['descricao'] }}</span>
                    </div>
                    <button wire:click="remover({{ $index }})" class="text-red-500 hover:text-red-700">🗑️</button>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">Nenhuma tarefa adicionada.</p>
    @endif
</div>
