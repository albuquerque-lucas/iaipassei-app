@extends('adminLayout')

@section('main-content')
<section x-data="{ activeTab: '{{ session('activeTab') ?: 'show' }}' }" class='admin-account-plans-page container mt-5'>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.account_plans.index') }}" class="btn btn-secondary">voltar</a>
        <ul class="nav nav-tabs" id="accountPlansTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button @click="activeTab = 'show'" :class="{ 'active': activeTab === 'show' }" class="nav-link" id="show-tab" type="button" role="tab" aria-controls="show" aria-selected="true">
                    Visualizar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button @click="activeTab = 'edit'" :class="{ 'active': activeTab === 'edit' }" class="nav-link" id="edit-tab" type="button" role="tab" aria-controls="edit" aria-selected="false">
                    Editar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button @click="activeTab = 'users'" :class="{ 'active': activeTab === 'users' }" class="nav-link" id="users-tab" type="button" role="tab" aria-controls="users" aria-selected="false">
                    Usuários
                </button>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="accountPlansTabContent">
        <div x-show="activeTab === 'show'" x-cloak class="tab-pane fade show active" id="show" role="tabpanel" aria-labelledby="show-tab">
            <div class="mt-4">
                <h4>Plano de Conta: {{ $accountPlan->name }}</h4>
                <hr>
                <h5>Descrição</h5>
                <p>{{ $accountPlan->description }}</p>
                <h5>Preço</h5>
                <p>{{ $accountPlan->price }}</p>
                <h5>Duração (dias)</h5>
                <p>{{ $accountPlan->duration_days }}</p>
                <h5>Nível de Acesso</h5>
                <p>{{ $accountPlan->access_level }}</p>
                <h5>Público</h5>
                <p>{{ $accountPlan->is_public ? 'Sim' : 'Não' }}</p>
            </div>
        </div>
        <div x-show="activeTab === 'edit'" x-cloak class="tab-pane fade show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
            <x-forms.edit-account-plan-form :accountPlan="$accountPlan" />
        </div>
        <div x-show="activeTab === 'users'" x-cloak class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
            <div class="mt-4">
                <h5>Usuários Associados</h5>
                <x-dashboards.users-account-plan-table :users="$users" :accountPlanId="$accountPlan->id" />
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                const activeTab = document.querySelector('#accountPlansTab .nav-link.active').getAttribute('aria-controls');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'activeTab';
                input.value = activeTab;
                form.appendChild(input);
            });
        });

        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const activeTab = document.querySelector('#accountPlansTab .nav-link.active').getAttribute('aria-controls');
                const url = new URL(link.href);
                url.searchParams.set('activeTab', activeTab);
                window.location.href = url.toString();
            });
        });
    });
</script>
@endsection
