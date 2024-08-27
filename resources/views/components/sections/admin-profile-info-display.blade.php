@props(['user'])

<div class="card mb-3 profile-info rounded-0">
    <div class="card-header d-flex align-items-center py-4">
        <img src="{{ $user->profile_img ? asset("storage/profile/$user->slug/" . $user->profile_img) : asset('storage/admin/profile/profile-no-image.jpg') }}"
            alt="Profile Image" class="ms-3 me-5 rounded-circle" style="width: 180px; height: 180px; object-fit: cover; box-shadow:1px 1px 3px #888">
        <h5 class="mb-0">{{ "$user->first_name $user->last_name" }}</h5>
    </div>
    <div class="card-body">
        <div class="info-section">
            @can('viewSensitiveInfo', $user)
            <h6 class="card-title">Informações Pessoais</h6>
            @endcan
            @cannot('viewSensitiveInfo', $user)
            <h6 class="card-title">Perfil</h6>
            @endcannot
            <div class="info-item d-flex justify-content-between">
                <p><strong>Usuário:</strong> {{ $user->username }}</p>
            </div>
        </div>

        @can('viewSensitiveInfo', $user)
        <div class="info-section">
            <div class="info-item d-flex justify-content-between">
                <p><strong>Email:</strong> {{ $user->email }}</p>
            </div>

            <div class="info-item d-flex justify-content-between">
                <p><strong>Verificado:</strong>
                    @if($user->email_verified_at)
                        <i class="fa-solid fa-check bg-success-subtle text-light p-1 rounded-pill"></i>
                    @else
                        <i class="fa-solid fa-xmark bg-danger text-light p-1 rounded-pill"></i>
                        <a href="{{ route('verification.send') }}"
                        onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();">
                            (Reenviar e-mail de confirmação)
                        </a>
                        <form id="resend-verification-form" action="{{ route('verification.send') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </p>
            </div>
            <div class="info-item d-flex justify-content-between" >
                <p><strong>Telefone:</strong> {{ $user->phone_number ?? 'Não informado' }}</p>
            </div>
        </div>

        <div class="info-section">
            <h6 class="card-title">Raça</h6>
            <div class="info-item d-flex justify-content-between">
                <p>{{ $user->race ?? 'Não informado' }}</p>
            </div>
        </div>

        <div class="info-section">
            <h6 class="card-title">Orientação Sexual e Gênero</h6>
            <div class="info-item d-flex justify-content-between">
                <p><strong>Sexo:</strong> {{ $user->sex ?? 'Não informado' }}</p>
            </div>

            <div class="info-item d-flex justify-content-between">
                <p><strong>Orientação:</strong> {{ $user->sexual_orientation ?? 'Não informado' }}</p>
            </div>

            <div class="info-item d-flex justify-content-between">
                <p><strong>Gênero:</strong> {{ $user->gender ?? 'Não informado' }}</p>
            </div>
        </div>

        <div class="info-section">
            <h6 class="card-title">Deficiência</h6>
            <div class="info-item d-flex justify-content-between">
                <p>{{ $user->disability ?? 'Não informado' }}</p>
            </div>
        </div>

        <div class="info-section">
            <h6 class="card-title">Conta</h6>
            <div class="info-item d-flex justify-content-between">
                <p>{{ $user->accountPlan->name ?? 'Não informado' }}</p>
            </div>
        </div>
        @endcan
    </div>
</div>
