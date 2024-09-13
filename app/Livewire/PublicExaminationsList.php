<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Examination;
use Exception;

class PublicExaminationsList extends Component
{
    use WithPagination;

    public $order = 'desc';
    public $orderBy = 'id';
    public $search = '';
    public $tempSearch = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function applySearch()
    {
        $this->search = $this->tempSearch;
        $this->resetPage();
    }

    public function render()
    {
        try {
            $query = Examination::query()
                ->when($this->search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%");
                })
                ->orderBy($this->orderBy, $this->order);

            $examinations = $query->paginate();

            return view('livewire.public-examinations-list', [
                'examinations' => $examinations,
            ]);
        } catch (Exception $e) {
            session()->flash('error', 'Erro ao carregar concursos: ' . $e->getMessage());
            return view('livewire.public-examinations-list', [
                'examinations' => collect(),
            ]);
        }
    }
}
