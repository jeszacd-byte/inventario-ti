@extends('layouts.app')
@section('title', 'Categorías')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h2 class="h4 mb-0">Gestión de categorías</h2>
        <small class="text-muted">Organiza los productos por tipo.</small>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva categoría</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Nombre</th><th>Descripción</th><th class="text-center">Productos</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td class="fw-semibold">{{ $category->name }}</td>
                    <td class="text-muted">{{ $category->description }}</td>
                    <td class="text-center"><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $category->products_count }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary btn-icon" title="Editar"><i class="bi bi-pencil-square"></i></a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline"
                              onsubmit="return confirm('¿Eliminar la categoría «{{ e($category->name) }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No hay categorías registradas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $categories->links() }}</div>
@endsection
