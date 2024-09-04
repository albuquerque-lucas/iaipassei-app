@props(['markedAlternatives', 'statistics'])

<div x-data="{
    highlight: JSON.parse(localStorage.getItem('highlight')) || false
}" x-init="
    $watch('highlight', value => {
        console.log('highlight changed to:', value ? 'destacado' : 'sem destaque');
        localStorage.setItem('highlight', JSON.stringify(value));
    })">
    <!-- Botão de Destacar -->
    <div class="d-flex justify-content-end mb-3 p-1">
        <button class="btn mx-1 w-8-rem rounded-0"
                :class="highlight ? 'btn-indigo-500' : 'btn-dark'"
                @click="highlight = !highlight">
            <span x-text="highlight ? 'sem destaque' : 'destacar'"></span>
            <i class="fa-solid fa-highlighter ms-1"></i>
        </button>
    </div>

    @if ($markedAlternatives->isEmpty())
        <p>Você não marcou nenhuma questão.</p>
    @else
        <ul class="list-group rounded-0 shadow">
            @foreach ($markedAlternatives as $alternative)
                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"
                    x-bind:class="highlight && {{ $statistics[$alternative->id]['is_max'] ? 'true' : 'false' }} ? 'result_correct__alternative' : (highlight ? 'result_incorrect__alternative' : '')">

                    <div class="w-100 w-md-25 mb-2 mb-md-0">
                        <span class="fw-bold d-inline-block" style="width: 150px;">Questão {{ $alternative->examQuestion->question_number }}:</span>
                        <span class="fw-bold d-inline-block" style="width: 50px;">{{ $alternative->letter }}</span>
                    </div>

                    <div class="w-100 w-md-25 d-flex justify-content-between justify-content-md-end align-items-center">
                        @can('viewAdminInfo')
                        <span>
                            {{ $statistics[$alternative->id]['users_with_alternative'] }} de {{ $statistics[$alternative->id]['total_users_for_question'] }} usuários
                            ({{ fmod($statistics[$alternative->id]['percentage'], 1) == 0 ? number_format($statistics[$alternative->id]['percentage'], 0) : number_format($statistics[$alternative->id]['percentage'], 2) }}%)
                        </span>
                        @endcan
                        <span class="ms-5 p-1">
                            @if($statistics[$alternative->id]['is_max'])
                                <i class="fa-solid fa-check bg-success text-light p-1 rounded-pill"></i>
                            @else
                                <i class="fa-solid fa-xmark bg-danger text-light p-1 rounded-pill"></i>
                            @endif
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
