<div>
    <button class="btn btn-dark mb-3 rounded-0" type="button" id="toggleFilterButton">
        Filtros
    </button>

    <div id="filterForm" style="display: none;">
        <form action="{{ $action }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <select name="order_by" class="form-control rounded-0">
                        <option value="id" {{ request('order_by') == 'id' ? 'selected' : '' }}>Ordenar por ID</option>
                        <option value="title" {{ request('order_by') == 'title' ? 'selected' : '' }}>Ordenar por Título</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="order" class="form-control rounded-0">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ordem Crescente</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Ordem Decrescente</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control rounded-0" placeholder="Pesquisar por título" value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark rounded-0">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('toggleFilterButton').addEventListener('click', () => {
        const filterForm = document.getElementById('filterForm');
        filterForm.style.display = filterForm.style.display === 'none' || filterForm.style.display === '' ? 'block' : 'none';
    });
</script>
