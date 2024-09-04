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
        <!-- Input de Pesquisa -->
        <input type="text" class="form-control w-25 rounded-0" placeholder="Pesquisar por título" x-model="search">

        <!-- Select para Ordenar Por -->
        <div class="d-flex">
            <select class="form-select me-2 rounded-0" x-model="orderBy">
                <option value="id">Ordenar por ID</option>
                <option value="title">Ordenar por Título</option>
            </select>

            <!-- Select para Ordem Crescente/Decrescente -->
            <select class="form-select rounded-0" x-model="orderDirection">
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
                    <th scope="col">Escolaridade</th>
                    <th scope="col" class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop Dinâmico via AlpineJS -->
                <template x-for="exam in filteredExams" :key="exam.id">
                    <tr>
                        <td x-text="exam.id"></td>
                        <td x-text="exam.title"></td>
                        <td x-text="exam.education_level ? exam.education_level.name : 'N/A'"></td>
                        <td class="text-end">
                            <!-- Rota para Edição com o Slug como segundo argumento -->
                            <a :href="'{{ route('admin.exams.edit', ':slug') }}'.replace(':slug', exam.slug)" class="btn btn-dark edit-btn btn-sm me-1 rounded-0">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <!-- Formulário para Exclusão com o Slug como segundo argumento -->
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
