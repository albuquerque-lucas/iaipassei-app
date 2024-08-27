@extends('publicLayout')

@section('main-content')
<div class="container mt-5 m-height-100" x-data="{
    highlight: JSON.parse(localStorage.getItem('highlight')) || false
}" x-init="
    $watch('highlight', value => {
        console.log('highlight changed to:', value ? 'destacado' : 'sem destaque');
        localStorage.setItem('highlight', JSON.stringify(value));
    })
">
    <h3 class="mb-4">Resultados</h3>

    <div class="d-flex align-items-center justify-content-between p-1">
        <ul class="nav nav-tabs" id="resultTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-0" id="question-results-tab" data-bs-toggle="tab" data-bs-target="#question-results" type="button" role="tab" aria-controls="question-results" aria-selected="true">
                    Resultados por Questão
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-0" id="complete-results-tab" data-bs-toggle="tab" data-bs-target="#complete-results" type="button" role="tab" aria-controls="complete-results" aria-selected="false">
                    Resultados Completos
                </button>
            </li>
        </ul>
        <div class="w-50 d-flex align-items-center justify-content-end">
            <button class="btn mx-1 w-25 rounded-0"
                    :class="highlight ? 'btn-indigo-500' : 'btn-dark'"
                    @click="highlight = !highlight">
                <span x-text="highlight ? 'Retirar Destaque' : 'Destacar'"></span>
            </button>

            <a href="{{ route('public.exams.show', $exam->slug) }}" class="btn btn-indigo-500 mx-1 rounded-0">
                Ver Simulado
                <i class="fa-solid fa-file-signature ms-1"></i>
            </a>
            <a href="{{ route('public.examinations.show', $exam->examination->slug) }}" class="btn btn-dark mx-1 rounded-0">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Conteúdo das Tabs -->
    <div class="tab-content mt-3 mb-5" id="resultTabsContent">
        <!-- Tab 1: Resultados por Questão -->
        <div class="tab-pane fade show active" id="question-results" role="tabpanel" aria-labelledby="question-results-tab">
            <x-sections.examResults.result-per-question
                :statistics="$statistics"
                :markedAlternatives="$markedAlternatives"
                x-bind:highlight="highlight"
            />
        </div>

        <!-- Tab 2: Resultados Completos -->
        <div class="tab-pane fade" id="complete-results" role="tabpanel" aria-labelledby="complete-results-tab">
            <x-sections.examResults.result-complete />
        </div>
    </div>
</div>
@endsection
