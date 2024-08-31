@props(['userRankings', 'userAnsweredAllQuestions', 'exam'])

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">

        <!-- Botão para calcular o ranking -->
        <button wire:click="loadUserRankings" class="btn btn-indigo-500 rounded-0">
            Calcular Ranking
            <i class="fa-solid fa-crown ms-1"></i>
        </button>

    </div>

    @if (!$userAnsweredAllQuestions)
        <p class="alert alert-warning">
            <strong>
                <i class="fa-solid fa-exclamation-triangle me-1"></i>
                Atenção:
            </strong>
            Você não respondeu todas as questões deste gabarito.
            Complete todas as questões para participar do ranking.
            <a href="{{ route('public.exams.show', $exam->slug) }}" class="alert-link">Clique aqui para continuar respondendo.</a>
        </p>
    @endif

    <!-- Spinner de carregamento -->
    <div class="text-center w-100 p-3">
    <div wire:loading>
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p>Calculando. Aguarde alguns instantes...</p>
        </div>
    </div>

    <!-- Conteúdo do ranking -->
    <div wire:loading.remove>
        <div class="d-flex align-items-center justify-content-center">
            @if (empty($userRankings))
                <p>Clique no botão para calcular a sua classificação.</p>
            @else

        </div>
        <table class="table table-striped shadow">
            <thead>
                <tr>
                    <th scope="col">Posição</th>
                    <th scope="col">Usuário</th>
                    <th scope="col">Respostas Corretas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userRankings as $index => $ranking)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $ranking['user']->username }}</td>
                        <td>{{ $ranking['correct_answers'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
