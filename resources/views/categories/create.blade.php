@extends('layouts.app')
@section('title', 'Nueva categoría')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">Nueva categoría</div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @include('categories._form', ['submit' => 'Guardar'])
        </form>
    </div>
</div>
@endsection
