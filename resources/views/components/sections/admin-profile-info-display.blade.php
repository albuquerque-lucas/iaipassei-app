@props(['user'])

<div class="card mb-3 profile-info">
    <div class="card-header d-flex align-items-center py-4">
        <img src="{{ $user->profile_img ? asset('storage/admin/profile/' . $user->profile_img) : asset('storage/admin/profile/profile-no-image.jpg') }}"
            alt="Profile Image" class="ms-3 me-5 rounded-circle" style="width: 180px; height: 180px; object-fit: cover; box-shadow:1px 1px 3px #888">
        <h5 class="mb-0">{{ "$user->first_name $user->last_name" }}</h5>
    </div>
    <div class="card-body">
        <div class="info-section">
            <h6 class="card-title">Informações Pessoais</h6>
            <div class="info-item d-flex justify-content-between">
                <p><strong>Usuário:</strong> {{ $user->username }}</p>
            </div>
            <div class="info-item d-flex justify-content-between">
                <p><strong>Email:</strong> {{ $user->email }}</p>
            </div>
            <div class="info-item d-flex justify-content-between">
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
    </div>
</div>
