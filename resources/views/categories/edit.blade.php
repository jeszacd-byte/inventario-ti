@extends('layouts.app')
@section('title', 'Editar categoría')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">Editar categoría</div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @method('PUT')
            @include('categories._form', ['submit' => 'Actualizar'])
        </form>
    </div>
</div>
@endsection
