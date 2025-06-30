<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tarefa;
use Illuminate\Support\Facades\Auth;

class GerenciarTarefa extends Component
{
    public $novaTarefa = [
        'titulo' => '',
        'descricao' => '',
        'status' => '',
    ];

    public function adicionar()
    {
        $this->validate(
            [
                'novaTarefa.titulo' => 'required|string|max:255',
                'novaTarefa.descricao' => 'required|string',
                'novaTarefa.status' => 'nullable|string',
            ],
            [
                'novaTarefa.titulo.required' => 'O título é obrigatório.',
                'novaTarefa.titulo.max' => 'O título deve ter no máximo 255 caracteres.',
                'novaTarefa.descricao.required' => 'A descrição é obrigatória.',
            ]
        );

        Tarefa::create([
            'titulo' => $this->novaTarefa['titulo'],
            'descricao' => $this->novaTarefa['descricao'],
            'status' => $this->novaTarefa['status'] ?: 'pendente',
            'user_id' => Auth::id(),
        ]);

        $this->novaTarefa = ['titulo' => '', 'descricao' => '', 'status' => ''];
    }

    public function remover($id)
    {
        $tarefa = Tarefa::where('id', $id)->where('user_id', Auth::id())->first();

        if ($tarefa) {
            $tarefa->delete();
        }
    }

    public function render()
    {
        $tarefas = Tarefa::where('user_id', Auth::id())->latest()->get();

        return view('livewire.gerenciar-tarefa', ['tarefas' => $tarefas]);
    }
}
