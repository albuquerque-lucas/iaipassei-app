<div class="mt-4">
    <h4>Configurações do Perfil</h4>
    <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Mostrar Nome de Usuário
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show_username" {{ $user->profileSettings->show_username ? 'checked' : '' }}>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Mostrar Email
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show_email" {{ $user->profileSettings->show_email ? 'checked' : '' }}>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Mostrar Sexo
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show_sex" {{ $user->profileSettings->show_sex ? 'checked' : '' }}>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Mostrar Orientação Sexual
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show_sexual_orientation" {{ $user->profileSettings->show_sexual_orientation ? 'checked' : '' }}>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Mostrar Gênero
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show_gender" {{ $user->profileSettings->show_gender ? 'checked' : '' }}>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Mostrar Raça
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show_race" {{ $user->profileSettings->show_race ? 'checked' : '' }}>
            </div>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Mostrar Deficiência
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="show_disability" {{ $user->profileSettings->show_disability ? 'checked' : '' }}>
            </div>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const switches = document.querySelectorAll('.form-check-input');

        switches.forEach(switchElement => {
            switchElement.addEventListener('change', function() {
                const setting = this.id;
                const value = this.checked;

                fetch(`/profile-settings/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        setting: setting,
                        value: value
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Configuração atualizada com sucesso.');
                    } else {
                        console.log('Erro ao atualizar configuração.');
                    }
                }).catch(error => console.log('Erro:', error));
            });
        });
    });
</script>

