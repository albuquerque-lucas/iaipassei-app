@props(['user'])

<form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="mt-4">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <h5>Informações Pessoais</h5>
        <label for="first_name" class="form-label">Primeiro Nome</label>
        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->first_name) }}">
        @error('first_name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="last_name" class="form-label">Último Nome</label>
        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->last_name) }}">
        @error('last_name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="username" class="form-label">Nome de Usuário</label>
        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}">
        @error('username')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}">
        @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="phone_number" class="form-label">Telefone</label>
        <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
        @error('phone_number')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="profile_img" class="form-label">Imagem de Perfil</label>
        <input id="profile_img" type="file" class="form-control @error('profile_img') is-invalid @enderror" name="profile_img">
        @error('profile_img')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-dark w-25">Atualizar</button>
    </div>
</form>
