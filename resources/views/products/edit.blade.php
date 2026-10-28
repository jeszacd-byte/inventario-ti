@extends('layouts.app')
@section('title', 'Editar producto')
@section('content')
<div class="card" style="max-width:860px">
    <div class="card-header">Editar producto</div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @method('PUT')
            @include('products._form', ['submit' => 'Actualizar'])
        </form>
    </div>
</div>
@endsection
