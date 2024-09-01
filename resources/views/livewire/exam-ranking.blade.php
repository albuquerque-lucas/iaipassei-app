@props(['userRankings', 'userAnsweredAllQuestions', 'exam'])

<div class="container mt-5">
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
    @if ($userRankings->isEmpty())
        <p>Nenhum usuário participou desta prova.</p>
    @else
    <table class="table table-striped shadow">
        <thead>
            <tr>
                <th scope="col" class="w-25">Posição</th>
                <th scope="col" class="w-50">Nome</th>
                <th scope="col" class="w-25 text-center">Respostas Corretas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($userRankings as $index => $ranking)
                <tr>
                    <th scope="row" class="w-25">{{ $index + 1 }}</th>
                    <td class="w-50">{{ $ranking->user->username }}</td>
                    <td class="w-25 text-center">{{ $ranking->correct_answers }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
