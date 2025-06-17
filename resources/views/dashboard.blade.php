@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard de Cotizaciones</h1>
    <a href="{{ route('quotations.create') }}" class="btn btn-primary mb-3">Nueva Cotización</a>
    <a href="{{ route('quotations.index') }}" class="btn btn-secondary mb-3">Ver Cotizaciones</a>
</div>
@endsection