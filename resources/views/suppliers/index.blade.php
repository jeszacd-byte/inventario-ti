@extends('layouts.app')
@section('title', 'Proveedores')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h2 class="h4 mb-0">Gestión de proveedores</h2>
        <small class="text-muted">Empresas que suministran los productos.</small>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo proveedor</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Dirección</th><th class="text-center">Productos</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            @forelse ($suppliers as $supplier)
                <tr>
                    <td class="fw-semibold">{{ $supplier->name }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td class="text-muted">{{ $supplier->address }}</td>
                    <td class="text-center"><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $supplier->products_count }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary btn-icon" title="Editar"><i class="bi bi-pencil-square"></i></a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="d-inline"
                              onsubmit="return confirm('¿Eliminar al proveedor «{{ e($supplier->name) }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No hay proveedores registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $suppliers->links() }}</div>
@endsection
