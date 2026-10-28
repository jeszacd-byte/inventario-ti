@extends('layouts.app')
@section('title', 'Nuevo proveedor')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header">Nuevo proveedor</div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @include('suppliers._form', ['submit' => 'Guardar'])
        </form>
    </div>
</div>
@endsection
