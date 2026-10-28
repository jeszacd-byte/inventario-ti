@csrf
<div class="mb-3">
    <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
    <input id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required maxlength="100"
           class="form-control @error('name') is-invalid @enderror" placeholder="Ej: Laptops">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="description" class="form-label">Descripción</label>
    <textarea id="description" name="description" rows="3" maxlength="1000"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Descripción de la categoría...">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> {{ $submit }}</button>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
