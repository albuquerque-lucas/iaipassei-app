@extends('publicLayout')

@section('main-content')
<div class="container mt-5 m-height-100">
    <h3 class="mb-4">Gabarito | Ranking</h3>

    <x-cards.flash-message-container />

    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between p-1">
        <ul class="nav nav-tabs w-100 w-md-auto mb-3 mb-md-0" id="resultTabs" role="tablist">
            <li class="nav-item me-1" role="presentation">
                <button class="nav-link rounded-0" id="exam-answer-form-tab" data-bs-toggle="tab" data-bs-target="#exam-answer-form" type="button" role="tab" aria-controls="exam-answer-form" aria-selected="false">
                    Gabarito
                </button>
            </li>
            <li class="nav-item me-1" role="presentation">
                <button class="nav-link rounded-0" id="complete-results-tab" data-bs-toggle="tab" data-bs-target="#complete-results" type="button" role="tab" aria-controls="complete-results" aria-selected="false">
                    Ranking
                </button>
            </li>
            <li class="nav-item me-1" role="presentation">
                <button class="nav-link rounded-0" id="question-results-tab" data-bs-toggle="tab" data-bs-target="#question-results" type="button" role="tab" aria-controls="question-results" aria-selected="false">
                    Questões
                </button>
            </li>
        </ul>

        <div class="w-100 w-md-50 d-flex align-items-center justify-content-start justify-content-md-end">
            <a href="{{ route('public.examinations.show', $exam->examination->slug) }}" class="btn btn-indigo-800-hover mx-1 rounded-0 w-8-tem w-md-25 shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Concurso
            </a>
        </div>
    </div>

    <div class="tab-content mt-3 mb-5" id="resultTabsContent">
        <div class="tab-pane fade" id="exam-answer-form" role="tabpanel" aria-labelledby="exam-answer-form-tab">
            <livewire:exam-answer-form :examId="$exam->id" :statistics="$statistics" :markedAlternatives="$markedAlternatives" />
        </div>

        <div class="tab-pane fade" id="complete-results" role="tabpanel" aria-labelledby="complete-results-tab">
            <livewire:exam-ranking :examId="$exam->id" :userAnsweredAllQuestions="$userAnsweredAllQuestions" />
        </div>

        <div class="tab-pane fade" id="question-results" role="tabpanel" aria-labelledby="question-results-tab">
            <x-sections.examResults.result-per-question :statistics="$statistics" :markedAlternatives="$markedAlternatives" />
        </div>
    </div>
</div>

@vite('resources/js/rank-page-tab-selection.js')
@endsection
