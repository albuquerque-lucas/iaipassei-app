@props(['exams'])

<div x-data="{
    search: '',
    orderBy: 'id',
    orderDirection: 'asc',
    exams: {{ json_encode($exams) }},
    get filteredExams() {
        let filtered = this.exams.filter(exam => exam.title.toLowerCase().includes(this.search.toLowerCase()));

        if (this.orderBy === 'id') {
            filtered.sort((a, b) => this.orderDirection === 'asc' ? a.id - b.id : b.id - a.id);
        } else if (this.orderBy === 'title') {
            filtered.sort((a, b) => this.orderDirection === 'asc'
                ? a.title.localeCompare(b.title)
                : b.title.localeCompare(a.title)
            );
        }
        return filtered;
    }
}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <input type="text" class="form-control w-25" placeholder="Pesquisar por título" x-model="search">

        <div class="d-flex">
            <select class="form-select me-2" x-model="orderBy">
                <option value="id">Ordenar por ID</option>
                <option value="title">Ordenar por Título</option>
            </select>

            <select class="form-select" x-model="orderDirection">
                <option value="asc">Ascendente</option>
                <option value="desc">Descendente</option>
            </select>
        </div>
    </div>

    <!-- Tabela -->
    <div class="table-responsive scrollable-list p-4 shadow border border-dark-subtle border-5 rounded-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Título</th>
                    <th scope="col" class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="exam in filteredExams" :key="exam.id">
                    <tr>
                        <td x-text="exam.id"></td>
                        <td x-text="exam.title"></td>
                        <td class="text-end">
                            <a :href="'{{ url('admin/exams/edit') }}/' + exam.slug" class="btn btn-dark edit-btn btn-sm me-1 rounded-0">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form :action="'{{ url('admin/exams/destroy') }}/' + exam.slug" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-dark delete-btn btn-sm rounded-0">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
