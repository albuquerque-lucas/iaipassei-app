@extends('publicLayout')

@section('main-content')
<div class="container mt-5">
    <h3 class="mb-4">Resultados</h3>

    <div class="d-flex align-items-center justify-content-between p-1">
        <ul class="nav nav-tabs" id="resultTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="question-results-tab" data-bs-toggle="tab" data-bs-target="#question-results" type="button" role="tab" aria-controls="question-results" aria-selected="true">
                    Resultados por Questão
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="complete-results-tab" data-bs-toggle="tab" data-bs-target="#complete-results" type="button" role="tab" aria-controls="complete-results" aria-selected="false">
                    Resultados Completos
                </button>
            </li>
        </ul>
        <div class="w-25 d-flex align-items-center justify-content-end">
            <a href="{{ route('public.examinations.show', $exam->examination->slug) }}" class="btn btn-secondary">
                Voltar
            </a>
        </div>
    </div>

    <!-- Conteúdo das Tabs -->
    <div class="tab-content mt-3" id="resultTabsContent">
        <!-- Tab 1: Resultados por Questão -->
        <div class="tab-pane fade show active" id="question-results" role="tabpanel" aria-labelledby="question-results-tab">
            <x-sections.examResults.result-per-question
                :statistics="$statistics"
                :markedAlternatives="$markedAlternatives"
            />
        </div>

        <!-- Tab 2: Resultados Completos -->
        <div class="tab-pane fade" id="complete-results" role="tabpanel" aria-labelledby="complete-results-tab">
            <x-sections.examResults.result-complete />
        </div>
    </div>
</div>
@endsection
