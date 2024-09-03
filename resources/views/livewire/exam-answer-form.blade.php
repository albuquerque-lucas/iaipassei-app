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
                                required>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex align-items-center mt-4 mb-5">
            <button type="submit" class="btn btn-dark rounded-0">
                Enviar gabarito
                @if ($isSubmitting)
                    <i class="fa-solid fa-spinner fa-spin ms-2"></i>
                @endif
            </button>
        </div>
    </form>
</section>
