<div class="container mt-5">
    @if ($userPosition)
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between p-1 mb-5 w-75 ranking-statistics-row mx-md-auto mx-auto">
            <span class="d-flex flex-column flex-lg-row align-items-center text-center mb-2 mb-lg-0">
                <strong>{{ __('Sua colocação:') }}</strong>
                <span class="ms-0 ms-lg-1">{{ $userPosition }}</span>
            </span>
            <span class="d-flex flex-column flex-lg-row align-items-center text-center mb-2 mb-lg-0">
                <strong>{{ __('Acertos:') }}</strong>
                <span class="ms-0 ms-lg-1">{{ $userCorrectAnswers }} questões</span>
            </span>
            <span class="d-flex flex-column flex-lg-row align-items-center text-center">
                <strong>{{ __('Porcentagem de acertos:') }}</strong>
                <span class="ms-0 ms-lg-1">{{ number_format($userPercentage, 2) }}%</span>
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

    <div class="mb-4 row align-items-center justify-content-start">
        <div class="col-12 col-lg-5">
            <input type="text" class="form-control rounded-0" placeholder="Buscar usuário..." wire:model.defer="search">
        </div>
        <div class="col-12 col-lg-2 mt-2 mt-lg-0">
            <button class="btn btn-indigo-800-hover w-100 rounded-0" type="button" wire:click="applyFilter" wire:loading.attr="disabled">
                <span wire:loading.remove>Buscar</span>
                <span wire:loading>
                    Buscando... <div class="spinner-border spinner-border-sm" role="status"></div>
                </span>
            </button>
        </div>
    </div>


    <div class="d-flex justify-content-center my-3">
        {{ $userRankings->links('pagination::bootstrap-4') }}
    </div>

    @if ($userRankings->isEmpty())
        <p>Nenhum usuário participou desta prova.</p>
    @else
        <div style="max-height: 80vh; overflow-y: scroll;" class="bg-light p-4 shadow">
            <table class="table table-striped shadow-sm">
                <thead class="table-dark">
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

        <div class="d-flex justify-content-center mt-4">
            {{ $userRankings->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
