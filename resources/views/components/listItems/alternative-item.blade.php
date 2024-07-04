@props(['alternative', 'editMode'])

<li class="list-group-item d-flex justify-content-between align-items-center">
    <span class="alternative-text" id="alternative-text-{{ $alternative->id }}">{{ $alternative->letter }}. {{ $alternative->text }}</span>
    <div class='btn-container' x-show="editMode">
        <button class="btn btn-sm btn-dark edit-btn edit-alternative-btn mx-2" data-bs-toggle="collapse" data-bs-target="#edit-alternative-{{ $alternative->id }}" aria-expanded="false" aria-controls="edit-alternative-{{ $alternative->id }}" onclick="toggleAlternativeText({{ $alternative->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Alternativa">
            <i class='fa-solid fa-edit'></i>
        </button>
        <button class="btn btn-sm btn-secondary delete-alternative-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteAlternativeModal{{ $alternative->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir Alternativa">
            <i class="fa-solid fa-trash"></i>
        </button>
    </div>

    <div class="collapse w-100 mt-2" id="edit-alternative-{{ $alternative->id }}">
        <x-forms.edit-alternative-form :alternative="$alternative"/>
    </div>
</li>
<x-popUps.confirm-delete-popUp :id="$alternative->id" :deleteRoute="'admin.question_alternatives.destroy'"/>


<script>
    function toggleAlternativeText(id) {
        const alternativeText = document.getElementById('alternative-text-' + id);
        if (alternativeText.style.display === 'none') {
            alternativeText.style.display = 'block';
        } else {
            alternativeText.style.display = 'none';
        }
    }
</script>

<style>
    .list-group-item {
        position: relative;
    }
    .btn-container {
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        height: 2rem;
        right: 1%;
        top: 10%;
    }
</style>
