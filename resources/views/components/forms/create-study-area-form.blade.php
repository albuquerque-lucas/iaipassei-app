<div class="mt-4">
    <form action="{{ route('admin.study_areas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <input type="text" class="form-control rounded-0 shadow-sm" id="name" name="name" placeholder="Nome" required>
        </div>
        <button type="submit" class="btn btn-dark w-25 rounded-0 shadow-sm">Adicionar</button>
    </form>
</div>
