@props(['markedAlternatives', 'statistics'])

<div x-data="{
    highlight: JSON.parse(localStorage.getItem('highlight')) || false
}" x-init="
    $watch('highlight', value => {
        console.log('highlight changed to:', value ? 'destacado' : 'sem destaque');
        localStorage.setItem('highlight', JSON.stringify(value));
    })">
    <!-- Botão de Destacar -->
    <div class="row mb-3 p-1">
        <div class="col-12 col-lg-3">
            <button class="btn w-100 rounded-0 btn-indigo-800-hover shadow-sm" type="button" @click="highlight = !highlight">
                <span x-text="highlight ? 'sem destaque' : 'destacar'"></span>
                <i class="fa-solid fa-highlighter ms-1"></i>
            </button>
        </div>
    </div>

    @if ($markedAlternatives->isEmpty())
        <p>Você não marcou nenhuma questão.</p>
    @else
        <ul class="list-group rounded-0 shadow">
            @foreach ($markedAlternatives as $alternative)
                <li class="list-group-item d-flex justify-content-between align-items-center"
                    x-bind:class="highlight && {{ $statistics[$alternative->id]['is_max'] ? 'true' : 'false' }} ? 'result_correct__alternative' : (highlight ? 'result_incorrect__alternative' : '')">

                    <div class="d-flex justify-content-start align-items-center">
                        <span class="fw-bold w-2-rem p-2">{{ $alternative->examQuestion->question_number }}:</span>
                        <span class="fw-bold p-2 d-flex align-items-center justify-content-center" style="min-width: 50px;">{{ $alternative->letter }}</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-end">
                        @can('viewAdminInfo')
                            <span class="text-md-end me-3">
                                {{ $statistics[$alternative->id]['users_with_alternative'] }} de {{ $statistics[$alternative->id]['total_users_for_question'] }} usuários
                                ({{ fmod($statistics[$alternative->id]['percentage'], 1) == 0 ? number_format($statistics[$alternative->id]['percentage'], 0) : number_format($statistics[$alternative->id]['percentage'], 2) }}%)
                            </span>
                        @endcan
                        <span class="p-1">
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
