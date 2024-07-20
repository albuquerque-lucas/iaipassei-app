@extends('publicLayout')

@section('main-content')
    <section class="container mt-5">
        @if(session('success'))
            <x-cards.flash-message-card type="success" :message="session('success')" />
        @endif

        @if($errors->any())
            @foreach ($errors->all() as $error)
                <x-cards.flash-message-card type="danger" :message="$error" />
            @endforeach
        @endif

        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item mx-1" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                    Informações
                </button>
            </li>
            @if(auth()->check() && auth()->user()->id == $user->id)
                <li class="nav-item mx-1" role="presentation">
                    <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab" aria-controls="edit" aria-selected="false">
                        Editar Perfil
                    </button>
                </li>
            @endif
            <li class="nav-item mx-1" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                    <i class="fa-solid fa-cog" aria-hidden="true"></i>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <x-sections.admin-profile-info-display :user="$user" />
            </div>
            @if(auth()->check() && auth()->user()->id == $user->id)
                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                    <x-forms.update-user-personal :user="$user" actionRoute="public.users.update"/>
                    <hr>
                    <x-forms.update-user-race-gender :user="$user" />
                    <hr>
                    <x-forms.update-user-disability :user="$user" />
                    <hr>
                    <x-forms.update-user-password :user="$user" />
                </div>
            @endif
            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <x-sections.edit-profile-settings-display :user="$user" />
            </div>
        </div>
    </section>
@endsection
