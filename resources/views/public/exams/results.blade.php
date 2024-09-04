@extends('publicLayout')

@section('main-content')
<div class="container mt-5 m-height-100">
    <h3 class="mb-4">Resultados</h3>

    <div class="d-flex align-items-center justify-content-between p-1">
        <ul class="nav nav-tabs" id="resultTabs" role="tablist">
            <li class="nav-item me-1" role="presentation">
                <button class="nav-link active rounded-0" id="complete-results-tab" data-bs-toggle="tab" data-bs-target="#complete-results" type="button" role="tab" aria-controls="complete-results" aria-selected="true">
                    ranking
                </button>
            </li>
            <li class="nav-item me-1" role="presentation">
                <button class="nav-link rounded-0" id="question-results-tab" data-bs-toggle="tab" data-bs-target="#question-results" type="button" role="tab" aria-controls="question-results" aria-selected="false">
                    questões
                </button>
            </li>
            <li class="nav-item me-1" role="presentation">
                <button class="nav-link rounded-0" id="exam-answer-form-tab" data-bs-toggle="tab" data-bs-target="#exam-answer-form" type="button" role="tab" aria-controls="exam-answer-form" aria-selected="false">
                    gabarito
                </button>
            </li>
        </ul>
        <div class="w-50 d-flex align-items-center justify-content-end">
            <a href="{{ route('public.examinations.show', $exam->examination->slug) }}" class="btn btn-dark mx-1 rounded-0 w-25">
                <i class="fa-solid fa-arrow-left me-1"></i>
                concurso
            </a>
        </div>
    </div>

    <div class="tab-content mt-3 mb-5" id="resultTabsContent">
        <div class="tab-pane fade show active" id="complete-results" role="tabpanel" aria-labelledby="complete-results-tab">
            <livewire:exam-ranking :examId="$exam->id" :userAnsweredAllQuestions="$userAnsweredAllQuestions" />
        </div>

        <div class="tab-pane fade" id="question-results" role="tabpanel" aria-labelledby="question-results-tab">
            <x-sections.examResults.result-per-question :statistics="$statistics" :markedAlternatives="$markedAlternatives" />
        </div>

        <div class="tab-pane fade" id="exam-answer-form" role="tabpanel" aria-labelledby="exam-answer-form-tab">
            <livewire:exam-answer-form :examId="$exam->id" />
        </div>
    </div>
</div>
@endsection
