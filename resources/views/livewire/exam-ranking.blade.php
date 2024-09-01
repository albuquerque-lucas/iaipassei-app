{{-- @props(['userRankings', 'userAnsweredAllQuestions', 'exam']) --}}

<div class="container mt-5">
    <h1>{{ $count }}</h1>

    <button wire:click="increment" class="btn btn-success mt-2">+</button>
    <button wire:click="decrement" class="btn btn-danger mt-2">-</button>

</div>

{{-- <div class="container mt-5"> --}}
    {{-- @if (!$userAnsweredAllQuestions)
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
        @if (empty($userRankings))
            <p>Nenhum ranking disponível.</p>
        @else
            <table class="table table-striped shadow">
                <thead>
                    <tr>
                        <th scope="col">Posição</th>
                        <th scope="col">Usuário</th>
                        <th scope="col">Respostas Corretas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userRankings as $ranking)
                        <tr>
                            <th scope="row">{{ $ranking->position }}</th>
                            <td>{{ $ranking->user->username }}</td>
                            <td>{{ $ranking->score }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div> --}}
{{-- </div> --}}
