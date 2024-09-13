<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Examination;
use Illuminate\Support\Facades\Auth;

class ExamsList extends Component
{
    use WithPagination;

    public $examination;
    public $search = '';
    public $tempSearch = '';
    public $filterEnrollmentStatus = 'all';
    public $tempFilterStatus = 'all';

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

    public function applyFilter()
    {
        $this->filterEnrollmentStatus = $this->tempFilterStatus;
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        $query = $this->examination->exams()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', "%{$this->search}%");
            })
            ->when($this->filterEnrollmentStatus == 'enrolled', function ($query) use ($user) {
                $query->whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            })
            ->when($this->filterEnrollmentStatus == 'not_enrolled', function ($query) use ($user) {
                $query->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            })
            ->paginate(10);

        return view('livewire.public.exams-list', [
            'exams' => $query
        ]);
    }
}
