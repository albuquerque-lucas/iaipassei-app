@props([
    'examination',
    'numExams',
    'numQuestionsPerExam',
    'numAlternativesPerQuestion',
    'studyAreas'
])

<div class="mt-4">
    <h4 class="mb-4 text-start">concurso</h4>

    <div class="card shadow-sm border-0 rounded-0 mb-4">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Título:</strong> {{ $examination->title }}
                </li>
                <li class="list-group-item">
                    <strong>Instituição:</strong> {{ $examination->institution }}
                </li>
                <li class="list-group-item">
                    <strong>Quantidade de Provas:</strong> {{ $numExams }}
                </li>
                <li class="list-group-item">
                    <strong>Áreas associadas:</strong>
                    <span>
                        @foreach($studyAreas as $studyArea)
                            {{ $studyArea->name }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-0 mt-5">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white rounded-0">
            <h5 class="mb-0">Lista de Editais</h5>
            <button type="button" class="btn btn-light btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#addNoticeModal" data-bs-toggle="tooltip" data-bs-placement="left" title="Adicionar Edital">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <x-listItems.notice-edit-list :notices="$examination->notice" />
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-0 mt-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Lista de Provas</h5>
        </div>
        <div class="card-body">
            <x-listItems.examination-exams-list :exams="$examination->exams" />
        </div>
    </div>
</div>

<x-popUps.add-exam-notice-popUp :examinationSlug="$examination->slug" />
