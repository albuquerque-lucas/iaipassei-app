@props(['userAnsweredAllQuestions', 'exam', 'isUpdating', 'userRankings', 'userPosition', 'userCorrectAnswers', 'userPercentage'])

<div class="container mt-5">
    <div class="h-5-rem">
        @if (session('success'))
            <x-cards.flash-message-card type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-cards.flash-message-card type="error" :message="session('error')" />
        @endif
    </div>

    {{-- Informações do usuário autenticado --}}
    @if ($userPosition)
        <div class="d-flex align-items-center p-2 mb-3">
            <span class="me-5">
                <strong>Sua colocação:</strong> {{ $userPosition }}
            </span>
            <span class="me-5">
                <strong>Acertos:</strong> {{ $userCorrectAnswers }} questões
            </span>
            <span class="me-5">
                <strong>Porcentagem de acertos:</strong> {{ number_format($userPercentage, 2) }}%
            </span>
        </div>
    @endif

    @if (!$userAnsweredAllQuestions)
        <p class="alert alert-warning text-start">
            <strong>
                <i class="fa-solid fa-exclamation-triangle me-1"></i>
                Atenção:
            </strong>
            Você não respondeu todas as questões deste gabarito.
            Complete todas as questões para participar do ranking.
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
                    <th scope="col" class="w-25 text-center">Total %</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userRankings as $index => $ranking)
                    @php
                        $isCurrentUser = $ranking->user->id === auth()->id();
                        $totalQuestions = $exam->examQuestions()->count();
                        $percentage = ($ranking->correct_answers / $totalQuestions) * 100;
                    @endphp
                    <tr class="{{ $isCurrentUser ? 'table-primary fw-bold' : '' }}">
                        <th scope="row" class="w-25">{{ $index + 1 }}</th>
                        <td class="w-50">{{ $ranking->user->username }}</td>
                        <td class="w-25 text-center">{{ number_format($percentage, 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
