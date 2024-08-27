@props(['userRankings'])

<div class="container mt-5">
    <h3 class="mb-4">Ranking de Usuários</h3>

    @if ($userRankings->isEmpty())
        <p>Nenhum usuário participou desta prova.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Posição</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Respostas Corretas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userRankings as $index => $ranking)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $ranking['user']->slug }}</td>
                        <td>{{ $ranking['correct_answers'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
