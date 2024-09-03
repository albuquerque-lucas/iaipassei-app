<section class="quiz-page container mt-5">
    <div class="mb-4 w-100 h-5-rem">
        @if (session('success'))
            <x-cards.flash-message-card type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-cards.flash-message-card type="error" :message="session('error')" />
        @endif
    </div>

    <form wire:submit.prevent="submit">
        <div class="row">
            @foreach ($questions as $question)
                <div class="col-md-2 mb-4">
                    <div class="card rounded-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center" style="min-height:3rem">
                                <h6 class="card-title fw-bold">{{ $question->question_number }} .</h6>
                            </div>
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
            <button type="submit" class="btn btn-dark rounded-0">
                Enviar gabarito
            </button>
            <div wire:loading class="w-25 text-dark">
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
