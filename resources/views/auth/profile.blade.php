@extends('adminLayout')

@section('main-content')
    <section class="container mt-5">
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                    Informações
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab" aria-controls="edit" aria-selected="false">
                    Editar Perfil
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <x-sections.admin-profile-info-display :user="Auth::user()" />
            </div>
            <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <x-forms.update-user-personal :user="Auth::user()" />
                <hr>
                <x-forms.update-user-race-gender :user="Auth::user()" />
                <hr>
                <x-forms.update-user-disability :user="Auth::user()" />
                <hr>
                <x-forms.update-user-password :user="Auth::user()" />
            </div>
        </div>
    </section>
@endsection
