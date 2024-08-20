@props(['markedAlternatives', 'statistics'])

<div>
    @if ($markedAlternatives->isEmpty())
        <p>Você não marcou nenhuma questão.</p>
    @else
        <ul class="list-group">
            @foreach ($markedAlternatives as $alternative)
                <li class="list-group-item d-flex justify-content-between"
                    x-bind:class="highlight && {{ $statistics[$alternative->id]['is_max'] ? 'true' : 'false' }} ? 'result_correct__alternative' : (highlight ? 'result_incorrect__alternative' : '')">
                    <div>
                        <span class="d-inline-block fw-bold" style="width: 150px;">Questão {{ $alternative->examQuestion->question_number }}:</span>
                        <span class="d-inline-block fw-bold" style="width: 50px;">{{ $alternative->letter }}</span>
                    </div>
                    <div>
                        <span>
                            {{ $statistics[$alternative->id]['users_with_alternative'] }} de {{ $statistics[$alternative->id]['total_users_for_question'] }} usuários
                            ({{ fmod($statistics[$alternative->id]['percentage'], 1) == 0 ? number_format($statistics[$alternative->id]['percentage'], 0) : number_format($statistics[$alternative->id]['percentage'], 2) }}%)
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
