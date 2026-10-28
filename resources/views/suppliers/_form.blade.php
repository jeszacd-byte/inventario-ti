@csrf
<div class="mb-3">
    <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
    <input id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required maxlength="150"
           class="form-control @error('name') is-invalid @enderror" placeholder="Ej: TechPeru S.A.C.">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" maxlength="255"
               class="form-control @error('email') is-invalid @enderror" placeholder="ventas@empresa.com">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label">Teléfono</label>
        <input id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" maxlength="30"
               class="form-control @error('phone') is-invalid @enderror" placeholder="987 654 321">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="mb-3">
    <label for="address" class="form-label">Dirección</label>
    <input id="address" name="address" value="{{ old('address', $supplier->address ?? '') }}" maxlength="255"
           class="form-control @error('address') is-invalid @enderror" placeholder="Av. Principal 123">
    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> {{ $submit }}</button>
    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
