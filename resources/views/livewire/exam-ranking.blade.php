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
        <div class="d-flex align-items-center p-1 mb-3 w-100">
            <span class="me-5 text-center">
                <strong>Sua colocação:</strong> {{ $userPosition }}
            </span>
            <span class="me-5 d-none d-md-inline text-center">
                <strong>Acertos:</strong> {{ $userCorrectAnswers }} questões
            </span>
            <span class="me-5 text-center">
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

    {{-- Campo de filtro e botão de filtrar --}}
    <div class="mb-4 d-flex align-items-center">
        <input type="text" class="form-control w-50 rounded-0" placeholder="Buscar usuário..." wire:model.defer="search">
        <button class="btn btn-indigo-800-hover ms-2 rounded-0 w-15" type="button" wire:click="applyFilter" wire:loading.attr="disabled">
            <span wire:loading.remove>Buscar</span>
            <span wire:loading>Buscando... <div class="spinner-border spinner-border-sm" role="status"></div></span>
        </button>
    </div>

    <div class="d-flex justify-content-center my-3">
        {{ $userRankings->links('pagination::bootstrap-4') }}
    </div>

    @if ($userRankings->isEmpty())
        <p>Nenhum usuário participou desta prova.</p>
    @else
        {{-- Tabela de rankings com paginação --}}
        <div style="max-height: 80vh; overflow-y: scroll;" class="bg-light p-3">
            <table class="table table-striped shadow">
                <thead>
                    <tr>
                        <th scope="col" class="w-25">Posição</th>
                        <th scope="col" class="w-50">Nome</th>
                        <th scope="col" class="w-25 text-center">Total %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userRankings as $ranking)
                        @php
                            $isCurrentUser = $ranking->user->id === auth()->id();
                            $totalQuestions = $exam->examQuestions()->count();
                            $percentage = ($ranking->correct_answers / $totalQuestions) * 100;
                        @endphp
                        <tr class="{{ $isCurrentUser ? 'table-primary fw-bold' : '' }}">
                            <th scope="row" class="w-25">{{ $ranking->position }}</th>
                            <td class="w-50">{{ $ranking->user->username }}</td>
                            <td class="w-25 text-center">{{ number_format($percentage, 2) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Controles de paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $userRankings->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
