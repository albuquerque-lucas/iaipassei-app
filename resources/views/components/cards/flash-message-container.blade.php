<div class="m-height-5-rem my-2 w-100">
    @if(session('success'))
        <x-cards.flash-message-card type="success" :message="session('success')" />
    @elseif(session('error'))
        <x-cards.flash-message-card type="error" :message="session('error')" />
    @endif
</div>
