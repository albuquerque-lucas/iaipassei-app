@props([
    'examination',
    'numExams',
    'numQuestionsPerExam',
    'numAlternativesPerQuestion',
    'studyAreas'
])

<div class="mt-4">
    <h4>Visualizar Concurso</h4>
    <p><strong>Título:</strong> {{ $examination->title }}</p>
    <p><strong>Instituição:</strong> {{ $examination->institution }}</p>
    <p><strong>Quantidade de Provas:</strong> {{ $numExams }}</p>

    <div class="">
        <strong>Áreas associadas:</strong>
        <p>
            @foreach($studyAreas as $studyArea)
                {{ $studyArea->name }}{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </p>
    </div>

    <div class="mt-5">
        <h5 class="d-flex justify-content-between align-items-center px-2">
            Lista de Editais
            <button type="button" class="btn btn-dark edit-btn btn-sm rounded-0" data-bs-toggle="modal" data-bs-target="#addNoticeModal" data-bs-toggle="tooltip" data-bs-placement="left" title="Adicionar Edital">
                <i class="fa-solid fa-plus"></i>
            </button>
        </h5>
        <x-listItems.notice-edit-list :notices="$examination->notice" />
    </div>

    <div class="mt-5">
        <h5>Lista de Provas</h5>
        <x-listItems.examination-exams-list :exams="$examination->exams" />
    </div>
</div>

<x-popUps.add-exam-notice-popUp :examinationSlug="$examination->slug" />
