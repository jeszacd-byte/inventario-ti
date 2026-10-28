@csrf
<div class="row">
    <div class="col-md-8 mb-3">
        <label for="name" class="form-label">Nombre del producto <span class="text-danger">*</span></label>
        <input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required maxlength="150"
               class="form-control @error('name') is-invalid @enderror" placeholder="Ej: Laptop HP 15&quot;">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
        <input id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required maxlength="50"
               class="form-control @error('sku') is-invalid @enderror" placeholder="Ej: SKU001">
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @else <div class="form-text">Único en el sistema.</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
        <select id="category_id" name="category_id" required class="form-select @error('category_id') is-invalid @enderror">
            <option value="">Selecciona una categoría</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="supplier_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
        <select id="supplier_id" name="supplier_id" required class="form-select @error('supplier_id') is-invalid @enderror">
            <option value="">Selecciona un proveedor</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id ?? '') == $supplier->id)>{{ $supplier->name }}</option>
            @endforeach
        </select>
        @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="price" class="form-label">Precio (S/) <span class="text-danger">*</span></label>
        <input id="price" type="number" name="price" step="0.01" min="0" required
               value="{{ old('price', $product->price ?? '') }}"
               class="form-control @error('price') is-invalid @enderror" placeholder="0.00">
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
        <input id="stock" type="number" name="stock" min="0" step="1" required
               value="{{ old('stock', $product->stock ?? 0) }}"
               class="form-control @error('stock') is-invalid @enderror">
        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @else <div class="form-text">Stock actual en inventario.</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="min_stock" class="form-label">Stock mínimo <span class="text-danger">*</span></label>
        <input id="min_stock" type="number" name="min_stock" min="0" step="1" required
               value="{{ old('min_stock', $product->min_stock ?? 5) }}"
               class="form-control @error('min_stock') is-invalid @enderror">
        @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @else <div class="form-text">Alerta de stock bajo.</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descripción</label>
    <textarea id="description" name="description" rows="3" maxlength="2000"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Descripción del producto...">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> {{ $submit }}</button>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
