@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('content')
<div class="card" style="max-width:860px">
    <div class="card-header">Nuevo producto</div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @include('products._form', ['submit' => 'Guardar'])
        </form>
    </div>
</div>
@endsection
