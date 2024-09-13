<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Examination;

class ExamsList extends Component
{
    use WithPagination;

    public $examination;
    public $search = '';
    public $tempSearch = '';

    protected $paginationTheme = 'bootstrap';

    public function mount(Examination $examination)
    {
        $this->examination = $examination;
    }

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
        $query = $this->examination->exams()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', "%{$this->search}%");
            })
            ->paginate(10);

        return view('livewire.public.exams-list', [
            'exams' => $query
        ]);
    }
}
