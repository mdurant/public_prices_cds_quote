@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Cotización</h1>
    <form action="{{ route('quotations.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="client_name" class="form-label">Nombre del Cliente</label>
            <input type="text" name="client_name" id="client_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="client_email" class="form-label">Email del Cliente</label>
            <input type="email" name="client_email" id="client_email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="client_phone" class="form-label">Teléfono del Cliente</label>
            <input type="text" name="client_phone" id="client_phone" class="form-control">
        </div>
        <div class="mb-3">
            <label for="product_search" class="form-label">Buscar Producto</label>
            <input type="text" id="product_search" class="form-control" placeholder="Escribe para buscar...">
            <div id="product_suggestions" class="list-group" style="display: none;"></div>
        </div>
        <div class="mb-3">
            <h3>Productos Seleccionados</h3>
            <table class="table table-bordered" id="selected_products">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Código Fonasa</th>
                        <th>Precio Fonasa</th>
                        <th>Precio Particular</th>
                        <th>Cantidad</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Generar Cotización</button>
    </form>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let selectedProducts = [];

    $('#product_search').on('input', function() {
        let query = $(this).val();
        if (query.length < 2) {
            $('#product_suggestions').hide();
            return;
        }

        $.ajax({
            url: '{{ route('products.search') }}',
            data: { query: query },
            success: function(data) {
                let suggestions = '';
                data.forEach(product => {
                    suggestions += `
                        <a href="#" class="list-group-item list-group-item-action product-item"
                           data-id="${product.id}"
                           data-description="${product.description}"
                           data-fonasa_code="${product.fonasa_code}"
                           data-fonasa_price="${product.fonasa_patient_price}"
                           data-private_price="${product.private_price}">
                            ${product.description} (${product.fonasa_code})
                        </a>`;
                });
                $('#product_suggestions').html(suggestions).show();
            }
        });
    });

    $(document).on('click', '.product-item', function(e) {
        e.preventDefault();
        let product = {
            id: $(this).data('id'),
            description: $(this).data('description'),
            fonasa_code: $(this).data('fonasa_code'),
            fonasa_price: $(this).data('fonasa_price'),
            private_price: $(this).data('private_price'),
            quantity: 1
        };

        if (!selectedProducts.find(p => p.id === product.id)) {
            selectedProducts.push(product);
            updateProductTable();
        }
        $('#product_search').val('');
        $('#product_suggestions').hide();
    });

    $(document).on('click', '.remove-product', function() {
        let id = $(this).data('id');
        selectedProducts = selectedProducts.filter(p => p.id !== id);
        updateProductTable();
    });

    $(document).on('change', '.product-quantity', function() {
        let id = $(this).data('id');
        let quantity = parseInt($(this).val()) || 1;
        let product = selectedProducts.find(p => p.id === id);
        if (product) {
            product.quantity = quantity;
            updateProductTable();
        }
    });

    function updateProductTable() {
        let tbody = $('#selected_products tbody');
        tbody.empty();
        selectedProducts.forEach(product => {
            tbody.append(`
                <tr>
                    <td>${product.description}</td>
                    <td>${product.fonasa_code}</td>
                    <td>${product.fonasa_price}</td>
                    <td>${product.private_price}</td>
                    <td>
                        <input type="number" class="form-control product-quantity" data-id="${product.id}" value="${product.quantity}" min="1" name="products[${product.id}][quantity]">
                        <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-product" data-id="${product.id}">Eliminar</button>
                    </td>
                </tr>
            `);
        });
    }
});
</script>
<style>
#product_suggestions { position: absolute; z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto; }
</style>
@endpush
@endsection