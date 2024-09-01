@props(['markedAlternatives', 'statistics'])

<div x-data="{
    highlight: JSON.parse(localStorage.getItem('highlight')) || false
}" x-init="
    $watch('highlight', value => {
        console.log('highlight changed to:', value ? 'destacado' : 'sem destaque');
        localStorage.setItem('highlight', JSON.stringify(value));
    })">
    <div class="d-flex justify-content-end mb-3 p-1">
        <button class="btn mx-1 w-15 rounded-0"
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
                <li class="list-group-item d-flex justify-content-between"
                    x-bind:class="highlight && {{ $statistics[$alternative->id]['is_max'] ? 'true' : 'false' }} ? 'result_correct__alternative' : (highlight ? 'result_incorrect__alternative' : '')">
                    <div class="w-25">
                        <span class="h-100 d-inline-block fw-bold" style="width: 150px;">Questão {{ $alternative->examQuestion->question_number }}:</span>
                        <span class="h-100 d-inline-block fw-bold" style="width: 50px;">{{ $alternative->letter }}</span>
                    </div>
                    <div class="w-25 d-flex align-items-center justify-content-end">
                        <span>
                            {{ $statistics[$alternative->id]['users_with_alternative'] }} de {{ $statistics[$alternative->id]['total_users_for_question'] }} usuários
                            ({{ fmod($statistics[$alternative->id]['percentage'], 1) == 0 ? number_format($statistics[$alternative->id]['percentage'], 0) : number_format($statistics[$alternative->id]['percentage'], 2) }}%)
                        </span>
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
