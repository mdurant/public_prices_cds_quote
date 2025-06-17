@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cotizaciones</h1>
    <a href="{{ route('quotations.create') }}" class="btn btn-primary mb-3">Crear Cotización</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Fecha</th>
                <th>Total Fonasa</th>
                <th>Total Particular</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotations as $quotation)
                <tr>
                    <td>{{ $quotation->id }}</td>
                    <td>{{ $quotation->client_name }}</td>
                    <td>{{ $quotation->client_email }}</td>
                    <td>{{ $quotation->quotation_date->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($quotation->total_fonasa_price, 2, ',', '.') }}</td>
                    <td>{{ number_format($quotation->total_private_price, 2, ',', '.') }}</td>
                    <td>{{ $quotation->deleted_at ? 'Eliminada' : 'Activa' }}</td>
                    <td>
                        <a href="{{ route('quotations.edit', $quotation) }}" class="btn btn-sm btn-primary">Editar</a>
                        @if ($quotation->deleted_at)
                            <form action="{{ route('quotations.restore', $quotation->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                            </form>
                        @else
                            <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar esta cotización?')">Eliminar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $quotations->links() }}
</div>
@endsection