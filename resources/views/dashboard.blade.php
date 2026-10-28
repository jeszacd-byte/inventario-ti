@extends('layouts.app')
@section('title', 'Panel principal')
@section('content')
<div class="mb-4">
    <h2 class="h4 mb-1">Bienvenido al inventario</h2>
    <p class="text-muted mb-0">Aquí tienes un resumen de tu inventario de accesorios informáticos.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <a href="{{ route('products.index') }}" class="text-decoration-none text-reset">
            <div class="card stat-card stat-blue">
                <div class="icon"><i class="bi bi-box-seam"></i></div>
                <div><div class="value">{{ $totalProducts }}</div><div class="label">Total de productos</div></div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('categories.index') }}" class="text-decoration-none text-reset">
            <div class="card stat-card stat-green">
                <div class="icon"><i class="bi bi-tags"></i></div>
                <div><div class="value">{{ $totalCategories }}</div><div class="label">Categorías</div></div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-reset">
            <div class="card stat-card stat-purple">
                <div class="icon"><i class="bi bi-truck"></i></div>
                <div><div class="value">{{ $totalSuppliers }}</div><div class="label">Proveedores</div></div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('products.index', ['low' => 1]) }}" class="text-decoration-none text-reset">
            <div class="card stat-card stat-red">
                <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div><div class="value">{{ $lowStockCount }}</div><div class="label">Con stock bajo</div></div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">Productos por categoría</div>
            <div class="card-body">
                @if ($byCategory->sum('products_count') > 0)
                    <div style="max-width:280px;margin:0 auto;position:relative">
                        <canvas id="categoryChart" aria-label="Productos por categoría" role="img"></canvas>
                    </div>
                    <ul class="list-unstyled mt-3 mb-0 small">
                        @foreach ($byCategory as $i => $cat)
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span><span class="legend-dot" data-i="{{ $i }}" style="display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:6px"></span>{{ $cat->name }}</span>
                                <strong>{{ $cat->products_count }}</strong>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center my-4">Aún no hay productos registrados.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Productos con stock bajo</span>
                <a href="{{ route('products.index', ['low' => 1]) }}" class="small text-decoration-none">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Producto</th><th>Categoría</th><th class="text-end">Stock</th><th class="text-end">Mínimo</th></tr></thead>
                    <tbody>
                    @forelse ($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}<div><span class="sku">{{ $product->sku }}</span></div></td>
                            <td>{{ $product->category->name }}</td>
                            <td class="text-end"><span class="badge bg-danger">{{ $product->stock }}</span></td>
                            <td class="text-end text-muted">{{ $product->min_stock }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-check-circle text-success"></i> Todo el stock está en orden.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if ($byCategory->sum('products_count') > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const labels = @json($byCategory->pluck('name'));
    const values = @json($byCategory->pluck('products_count'));
    const colors = ['#0d6efd', '#198754', '#6f42c1', '#fd7e14', '#20c997', '#d63384', '#0dcaf0', '#6c757d'];
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: { labels, datasets: [{ data: values, backgroundColor: labels.map((_, i) => colors[i % colors.length]), borderWidth: 2 }] },
        options: { plugins: { legend: { display: false } }, cutout: '62%' }
    });
    document.querySelectorAll('.legend-dot').forEach(el => el.style.background = colors[el.dataset.i % colors.length]);
</script>
@endif
@endpush
