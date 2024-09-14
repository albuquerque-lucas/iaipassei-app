<section class="quiz-page container mt-5">
    <form wire:submit.prevent="submit">
        <div class="row">
            @foreach ($questions as $question)
                @php
                    $alternativeLetter = $markedAlternatives[$question->id] ?? null;
                    $alternative = isset($alternativeLetter) ? collect($statistics)->firstWhere('alternative.letter', $alternativeLetter) : null;
                    $alternativeId = $alternative['alternative']->id ?? null;
                @endphp
                <div class="col-md-2 mb-4">
                    <div class="card rounded-0 shadow-sm {{ isset($alternative) && $alternative['is_max'] ? 'result_correct__alternative' : (isset($alternative) ? 'result_incorrect__alternative' : '') }}">
                        <div class="ps-3 pe-1 d-flex align-items-center justify-content-between">
                            <div class="d-flex justify-content-between align-items-center" style="min-height:3rem">
                                <h6 class="card-title fw-bold">{{ $question->question_number }}.</h6>
                            </div>
                            @if(isset($alternative))
                                <span class="m-h-2-r d-flex align-items-center justify-content-center me-1">
                                    @if($alternative['is_max'])
                                        <i class="fa-solid fa-check bg-success text-light p-1 rounded-pill"></i>
                                    @else
                                        <i class="fa-solid fa-xmark bg-danger text-light p-1 rounded-pill"></i>
                                    @endif
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                {{ $question->statement }}
                            </p>

                            <input
                                type="text"
                                class="form-control question-input"
                                wire:model.defer="markedAlternatives.{{ $question->id }}"
                                maxlength="1"
                                oninput="validateInput(this)"
                                required>
                        </div>


                    </div>
                </div>
            @endforeach

        </div>

        <div class="d-flex align-items-center mt-4 mb-5 justify-content-between">
            <button type="submit" class="btn btn-indigo-800-hover rounded-0 w-25">
                Enviar gabarito
            </button>
            <div wire:loading class="w-50 text-dark">
                <div class="d-flex h-100 w-100 align-items-center justify-content-end">
                    <p class="p-0 m-0">Calculando ranking. Aguarde alguns instantes...</p>
                    <i class="fa-solid fa-spinner fa-spin ms-2"></i>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    function validateInput(input) {
        const value = input.value;
        if (value.length > 1 || /[0-9]/.test(value)) {
            input.value = '';
        }
    }
</script>
