@extends('layouts.app')
@section('title', 'Editar proveedor')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header">Editar proveedor</div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @method('PUT')
            @include('suppliers._form', ['submit' => 'Actualizar'])
        </form>
    </div>
</div>
@endsection
