@extends('layouts.app')
@section('title', 'Movimientos de stock')
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Registrar movimiento de stock</div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock-movements.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Producto <span class="text-danger">*</span></label>
                        <select id="product_id" name="product_id" required class="form-select @error('product_id') is-invalid @enderror">
                            <option value="">Seleccionar producto</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id', request('product_id')) == $product->id)>
                                    {{ $product->name }} — Stock: {{ $product->stock }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de movimiento <span class="text-danger">*</span></label>
                        <select id="type" name="type" required class="form-select @error('type') is-invalid @enderror">
                            <option value="entrada" @selected(old('type') === 'entrada')>Entrada</option>
                            <option value="salida" @selected(old('type') === 'salida')>Salida</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad <span class="text-danger">*</span></label>
                        <input id="quantity" type="number" name="quantity" min="1" step="1" required value="{{ old('quantity') }}"
                               class="form-control @error('quantity') is-invalid @enderror">
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Motivo</label>
                        <input id="reason" type="text" name="reason" maxlength="255" value="{{ old('reason') }}"
                               class="form-control @error('reason') is-invalid @enderror" placeholder="Ej: Reposición, Entrega, Venta">
                        @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check2-circle"></i> Registrar movimiento</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Historial de movimientos</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr><th>Fecha</th><th>Producto</th><th>Tipo</th><th class="text-end">Cantidad</th><th class="text-end">Stock anterior</th><th class="text-end">Stock nuevo</th><th>Motivo</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td class="text-nowrap">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>
                                @if ($movement->type === 'entrada')
                                    <span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-arrow-down-circle"></i> Entrada</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger-emphasis"><i class="bi bi-arrow-up-circle"></i> Salida</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold {{ $movement->type === 'entrada' ? 'text-success' : 'text-danger' }}">
                                {{ $movement->type === 'entrada' ? '+' : '-' }}{{ $movement->quantity }}
                            </td>
                            <td class="text-end">{{ $movement->stock_before }}</td>
                            <td class="text-end fw-semibold">{{ $movement->stock_after }}</td>
                            <td class="text-muted">{{ $movement->reason }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Aún no hay movimientos registrados.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $movements->links() }}</div>
    </div>
</div>
@endsection
