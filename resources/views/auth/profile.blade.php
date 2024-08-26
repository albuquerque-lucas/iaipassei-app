@extends('adminLayout')

@section('main-content')
    <section class="container mt-5">
        <div style="height:5rem">
            @if(session('success'))
                <x-cards.flash-message-card type="success" :message="session('success')" />
            @endif

            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <x-cards.flash-message-card type="danger" :message="$error" />
                @endforeach
            @endif
        </div>

        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item me-1" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    Perfil
                </button>
            </li>
            @if(auth()->user()->id == $user->id)
                <li class="nav-item me-1" role="presentation">
                    <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab" aria-controls="edit" aria-selected="false">
                        <i class="fa-solid fa-pen-to-square me-1"></i>
                        Editar
                    </button>
                </li>
            @endif
        </ul>

        <div class="tab-content" id="profileTabContent">
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <x-sections.admin-profile-info-display :user="$user" />
            </div>
            @if(auth()->user()->id == $user->id)
                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                    <x-forms.update-user-personal :user="$user" actionRoute="admin.users.update"/>
                    <hr>
                    <x-forms.update-user-race-gender :user="$user" />
                    <hr>
                    <x-forms.update-user-disability :user="$user" />
                    <hr>
                    <x-forms.update-user-password :user="$user" />
                </div>
            @endif
        </div>
    </section>
@endsection
