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
    <!-- Barra de Pesquisa e Ordenação -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <!-- Campo de Pesquisa -->
        <input type="text" class="form-control w-100 w-md-25 mb-3 mb-md-0 rounded-0" placeholder="Pesquisar por título" x-model="search">

        <!-- Campos de Ordenação -->
        <div class="d-flex flex-column flex-md-row w-100 w-md-auto">
            <select class="form-select mb-3 mb-md-0 me-md-2 rounded-0" x-model="orderBy">
                <option value="id">Ordenar por ID</option>
                <option value="title">Ordenar por Título</option>
            </select>

            <select class="form-select rounded-0" x-model="orderDirection">
                <option value="asc">Ascendente</option>
                <option value="desc">Descendente</option>
            </select>
        </div>
    </div>

    <!-- Tabela Responsiva -->
    <div class="table-responsive p-4 border-top border-2 border-dark-subtle rounded-0">
        <table class="table table-striped table-hover shadow">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Título</th>
                    <th scope="col">Escolaridade</th>
                    <th scope="col" class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iteração sobre os exames filtrados -->
                <template x-for="exam in filteredExams" :key="exam.id">
                    <tr>
                        <td x-text="exam.id"></td>
                        <td x-text="exam.title"></td>
                        <td x-text="exam.education_level ? exam.education_level.name : 'N/A'"></td>
                        <td class="text-end">
                            <a :href="'{{ route('admin.exams.edit', ':slug') }}'.replace(':slug', exam.slug)" class="btn btn-dark edit-btn btn-sm me-1 rounded-0">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form :action="'{{ route('admin.exams.destroy', ':slug') }}'.replace(':slug', exam.slug)" method="POST" class="d-inline">
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
