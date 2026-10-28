@extends('layouts.app')
@section('title', 'Productos')
@section('content')
@php
    $curSort = request('sort', 'name');
    $curDir = request('dir', 'asc');
    // Enlace de ordenamiento que conserva búsqueda y filtros
    $sortUrl = fn (string $col) => route('products.index', array_merge(
        request()->except('page'),
        ['sort' => $col, 'dir' => ($curSort === $col && $curDir === 'asc') ? 'desc' : 'asc']
    ));
    $sortIcon = fn (string $col) => $curSort === $col
        ? ($curDir === 'asc' ? 'bi-sort-up' : 'bi-sort-down')
        : 'bi-arrow-down-up text-muted';
    $hasFilters = request()->hasAny(['q', 'category_id', 'supplier_id', 'low', 'sort']);
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h2 class="h4 mb-0">Listado de productos</h2>
        <small class="text-muted">Busca por nombre o SKU y filtra por categoría o proveedor.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.export', request()->query()) }}" class="btn btn-outline-success"><i class="bi bi-filetype-csv"></i> Exportar CSV</a>
        <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo producto</a>
    </div>
</div>

{{-- Tarjetas resumen (opcional 3) --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card stat-card stat-blue">
            <div class="icon"><i class="bi bi-box-seam"></i></div>
            <div><div class="value">{{ $totalProducts }}</div><div class="label">Total de productos</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('products.index', ['low' => 1]) }}" class="text-decoration-none text-reset">
            <div class="card stat-card stat-red">
                <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div><div class="value">{{ $lowStockCount }}</div><div class="label">Con stock bajo</div></div>
            </div>
        </a>
    </div>
</div>

{{-- Búsqueda y filtros (RF06, RF07) --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-end">
            <div class="col-lg-4">
                <label class="form-label mb-1" for="q">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input id="q" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nombre o SKU">
                </div>
            </div>
            <div class="col-md-6 col-lg-2">
                <label class="form-label mb-1" for="category_id">Categoría</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-lg-2">
                <label class="form-label mb-1" for="supplier_id">Proveedor</label>
                <select id="supplier_id" name="supplier_id" class="form-select">
                    <option value="">Todos los proveedores</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(request('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-lg-2">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="low" name="low" value="1" @checked(request()->boolean('low'))>
                    <label class="form-check-label" for="low">Solo stock bajo</label>
                </div>
            </div>
            <div class="col-md-6 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel"></i> Buscar</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Limpiar búsqueda y filtros">Limpiar</a>
            </div>
            @if (request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="dir" value="{{ request('dir', 'asc') }}">
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>SKU</th>
                <th><a class="sort-link" href="{{ $sortUrl('name') }}">Producto <i class="bi {{ $sortIcon('name') }}"></i></a></th>
                <th>Categoría</th>
                <th>Proveedor</th>
                <th class="text-end"><a class="sort-link" href="{{ $sortUrl('price') }}">Precio <i class="bi {{ $sortIcon('price') }}"></i></a></th>
                <th class="text-end"><a class="sort-link" href="{{ $sortUrl('stock') }}">Stock <i class="bi {{ $sortIcon('stock') }}"></i></a></th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                <tr class="{{ $product->isLowStock() ? 'row-low' : '' }}">
                    <td><span class="sku">{{ $product->sku }}</span></td>
                    <td class="fw-semibold">{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->supplier->name }}</td>
                    <td class="text-end">S/ {{ number_format($product->price, 2) }}</td>
                    <td class="text-end">{{ $product->stock }} <small class="text-muted">/ mín. {{ $product->min_stock }}</small></td>
                    <td>
                        @if ($product->isLowStock())
                            <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill"></i> Stock bajo</span>
                        @else
                            <span class="badge bg-success">Stock disponible</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('stock-movements.index', ['product_id' => $product->id]) }}" class="btn btn-outline-secondary btn-icon" title="Registrar movimiento"><i class="bi bi-arrow-left-right"></i></a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-icon" title="Editar"><i class="bi bi-pencil-square"></i></a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline"
                              onsubmit="return confirm('¿Eliminar el producto «{{ e($product->name) }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                        {{ $hasFilters ? 'No se encontraron productos con esa búsqueda o filtro.' : 'No hay productos registrados.' }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
    <small class="text-muted">Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} de {{ $products->total() }} registros</small>
    {{ $products->links() }}
</div>
@endsection
