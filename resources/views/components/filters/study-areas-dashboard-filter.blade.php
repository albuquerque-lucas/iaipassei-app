<div>
    <button class="btn btn-secondary mb-3" type="button" id="toggleFilterButton">
        Mostrar/Ocultar Filtros
    </button>

    <div id="filterForm" style="display: none;">
        <form action="{{ $action }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <select name="order_by" class="form-control">
                        <option value="id" {{ request('order_by') == 'id' ? 'selected' : '' }}>Ordenar por ID</option>
                        <option value="name" {{ request('order_by') == 'name' ? 'selected' : '' }}>Ordenar por Nome</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="order" class="form-control">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ordem Crescente</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Ordem Decrescente</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark">Filtrar</button>
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
