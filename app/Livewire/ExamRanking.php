<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ExamRanking extends Component
{
    public $examId;
    public Exam $exam;
    public $userRankings = [];
    public bool $userAnsweredAllQuestions;
    public bool $isUpdating = false;
    public $userPosition;
    public $userCorrectAnswers;
    public $userPercentage;
    public $search = ''; // Campo para o filtro de username

    public function mount($examId)
    {
        $this->examId = $examId;
        $this->exam = Exam::findOrFail($examId);
        $this->loadUserRankings();
    }

    public function applyFilter()
    {
        $this->loadUserRankings();
    }

    public function loadUserRankings()
    {
        $this->isUpdating = true;

        // Buscar rankings com base no filtro de username
        $this->userRankings = $this->exam->rankings()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('username', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('position')
            ->get();

        $this->calculateUserStats();

        $this->isUpdating = false;
    }

    protected function calculateUserStats()
    {
        $userId = auth()->id();
        $totalQuestions = $this->exam->examQuestions()->count();

        foreach ($this->userRankings as $index => $ranking) {
            if ($ranking->user_id == $userId) {
                $this->userPosition = $index + 1;
                $this->userCorrectAnswers = $ranking->correct_answers;
                $this->userPercentage = ($ranking->correct_answers / $totalQuestions) * 100;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.exam-ranking', [
            'userRankings' => $this->userRankings,
            'isUpdating' => $this->isUpdating,
            'userPosition' => $this->userPosition,
            'userCorrectAnswers' => $this->userCorrectAnswers,
            'userPercentage' => $this->userPercentage,
        ]);
    }
}
