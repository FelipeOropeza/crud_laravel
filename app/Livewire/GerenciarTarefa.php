<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Models\Tarefa;
use Illuminate\Support\Facades\Auth;

class GerenciarTarefa extends Component
{
    public $modalAberto = false;
    public $search = '';
    public $novaTarefa = [
        'titulo' => '',
        'descricao' => '',
        'status' => '',
    ];
    public $tarefaEditada = [
        'id' => null,
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

    public function abrirModalEditar($id)
    {
        $tarefa = Tarefa::where('id', $id)->where('user_id', Auth::id())->first();

        if ($tarefa) {
            $this->tarefaEditada = [
                'id' => $tarefa->id,
                'titulo' => $tarefa->titulo,
                'descricao' => $tarefa->descricao,
                'status' => $tarefa->status,
            ];

            $this->modalAberto = true;
        }
    }

    public function salvarAtualizacao()
    {
        $this->validate([
            'tarefaEditada.titulo' => 'required|string|max:255',
            'tarefaEditada.descricao' => 'required|string',
            'tarefaEditada.status' => 'nullable|string',
        ], [
            'tarefaEditada.titulo.required' => 'O título é obrigatório.',
            'tarefaEditada.titulo.max' => 'O título deve ter no máximo 255 caracteres.',
            'tarefaEditada.descricao.required' => 'A descrição é obrigatória.',
        ]);

        $tarefa = Tarefa::where('id', $this->tarefaEditada['id'])
            ->where('user_id', Auth::id())
            ->first();

        if ($tarefa) {
            $tarefa->update([
                'titulo' => $this->tarefaEditada['titulo'],
                'descricao' => $this->tarefaEditada['descricao'],
                'status' => $this->tarefaEditada['status'] ?: 'pendente',
            ]);
        }

        $this->modalAberto = false;
    }

    public function render()
    {
        $tarefas = Tarefa::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('titulo', 'like', '%' . $this->search . '%')
                    ->orWhere('descricao', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        return view('livewire.gerenciar-tarefa', ['tarefas' => $tarefas]);
    }
}
