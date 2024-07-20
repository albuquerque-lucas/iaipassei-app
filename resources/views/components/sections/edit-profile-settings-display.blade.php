<div class="mt-4">
    <h4>Configurações do Perfil</h4>
    <ul class="list-group list-group-flush" id="settings-list">
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
    <div class="mt-3 d-none" id="buttons-container">
        <button class="btn btn-primary" id="save-button">Salvar</button>
        <button class="btn btn-secondary" id="cancel-button">Cancelar</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const switches = document.querySelectorAll('.form-check-input');
        const buttonsContainer = document.getElementById('buttons-container');
        let originalSettings = {};
        let modifiedSettings = [];

        // Salvar os estados originais
        switches.forEach(switchElement => {
            originalSettings[switchElement.id] = switchElement.checked;
        });

        switches.forEach(switchElement => {
            switchElement.addEventListener('change', function() {
                const setting = this.id;
                const value = this.checked;

                // Verificar se a configuração foi modificada
                if (originalSettings[setting] !== value) {
                    if (!modifiedSettings.includes(setting)) {
                        modifiedSettings.push(setting);
                    }
                } else {
                    const index = modifiedSettings.indexOf(setting);
                    if (index > -1) {
                        modifiedSettings.splice(index, 1);
                    }
                }

                // Mostrar ou esconder os botões
                if (modifiedSettings.length > 0) {
                    buttonsContainer.classList.remove('d-none');
                } else {
                    buttonsContainer.classList.add('d-none');
                }
            });
        });

        // Salvar alterações
        document.getElementById('save-button').addEventListener('click', function() {
            const settingsToUpdate = {};
            modifiedSettings.forEach(setting => {
                const switchElement = document.getElementById(setting);
                settingsToUpdate[setting] = switchElement.checked;
            });

            fetch(`/profile-settings/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(settingsToUpdate)
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Configurações atualizadas com sucesso.');
                    // Atualizar os estados originais
                    modifiedSettings.forEach(setting => {
                        originalSettings[setting] = document.getElementById(setting).checked;
                    });
                    modifiedSettings = [];
                    buttonsContainer.classList.add('d-none');
                    // Recarregar a página
                    location.reload();
                } else {
                    console.log('Erro ao atualizar configurações.');
                }
            }).catch(error => console.log('Erro:', error));
        });

        // Cancelar alterações
        document.getElementById('cancel-button').addEventListener('click', function() {
            switches.forEach(switchElement => {
                switchElement.checked = originalSettings[switchElement.id];
            });
            modifiedSettings = [];
            buttonsContainer.classList.add('d-none');
        });
    });
</script>
