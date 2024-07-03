@extends('adminLayout')

@section('main-content')
<section class='edit-exam-page container mt-5'>
    <x-tabs.tabs :backRoute="route('admin.examinations.edit', $exam->examination->id)" />

    @if (session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif

    <div class="tab-content" id="editExamTabContent">
        <div class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
            <x-cards.exam-info-card :exam="$exam" />

            <h4 class="mt-5">Questões</h4>
            @foreach ($examQuestions as $question)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Questão {{ $question->question_number }}</h5>
                        <p class="card-text">{{ $question->statement ?? 'Enunciado não informado' }}</p>
                        <ul class="list-group">
                            @foreach ($question->alternatives as $alternative)
                                <li class="list-group-item list-group-item-action" style="cursor:pointer">{{ $alternative->letter }}. {{ $alternative->text }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $examQuestions->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <div class="mt-4">
                <h4>Editar Prova</h4>
                <x-forms.edit-exam-form :exam="$exam" />
            </div>
        </div>
    </div>
</section>
@endsection
